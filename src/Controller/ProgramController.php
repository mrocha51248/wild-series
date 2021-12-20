<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Service\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        Slugify $slugify,
        MailerInterface $mailer
    ): Response {
        $program = (new Program())
            ->setOwner($this->getUser());

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));
            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', [
            "program" => $program,
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="show", methods="GET")
     */
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="edit")
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        Program $program,
        Slugify $slugify
    ): Response {
        if ($this->getUser() !== $program->getOwner() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Only the owner can edit the program!');
        }
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/edit.html.twig', [
            "program" => $program,
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="delete", methods="POST")
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, Program $program): Response
    {
        if ($this->getUser() !== $program->getOwner() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Only the owner can remove the program!');
        }
        if ($this->isCsrfTokenValid('delete' . $program->getId(), $request->request->get('_token'))) {
            $entityManager->remove($program);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{programSlug}/season/{seasonSlug}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programSlug": "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonSlug": "slug"}})
     */
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    /**
     * @Route("/{programSlug}/season/{seasonSlug}/episode/{episodeSlug}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programSlug": "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonSlug": "slug"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeSlug": "slug"}})
     */
    public function showEpisode(
        Request $request,
        EntityManagerInterface $entityManager,
        Program $program,
        Season $season,
        Episode $episode
    ): Response {
        $comment = (new Comment())
            ->setAuthor($this->getUser())
            ->setEpisode($episode);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

            $entityManager->persist($comment);
            $entityManager->flush();

            $routeName = $request->attributes->get('_route');
            $routeParameters = $request->attributes->get('_route_params');
            return $this->redirectToRoute($routeName, $routeParameters);
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/comment/{id}", name="comment_delete", methods="POST")
     */
    public function deleteComment(Request $request, EntityManagerInterface $entityManager, Comment $comment): Response
    {
        if ($this->getUser() !== $comment->getAuthor() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Only the author can remove the comment!');
        }
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_episode_show', [
            'programSlug' => $comment->getEpisode()->getSeason()->getProgram()->getSlug(),
            'seasonSlug' => $comment->getEpisode()->getSeason()->getSlug(),
            'episodeSlug' => $comment->getEpisode()->getSlug(),
        ], Response::HTTP_SEE_OTHER);
    }
}
