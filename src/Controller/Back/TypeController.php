<?php

namespace App\Controller\Back;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type')]
class TypeController extends AbstractController
{
    #[Route('/', name: 'app_type_index', methods: ['GET'])]
    public function index(TypeRepository $typeRepository): Response
    {
        return $this->render('back/type/index.html.twig', [
            'types' => $typeRepository->findBy([], ['position' => 'ASC']),
        ]);
    }

    #[Route('/{id}/{sortable}', name: 'app_type_sortable', requirements: ['id' => '\d+', 'sortable' => 'UP|DOWN'], methods: ['GET'])]
    public function sortable(Type $type, string $sortable, EntityManagerInterface $manager): Response
    {
        $sortable === 'UP' ? $type->setPosition($type->getPosition() -1) : $type->setPosition($type->getPosition() +1);
        $manager->flush();

        return $this->redirectToRoute('back_app_type_index');
    }

    #[Route('/new', name: 'app_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TypeRepository $typeRepository): Response
    {
        $type = new Type();
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeRepository->save($type, true);

            return $this->redirectToRoute('back_app_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/type/new.html.twig', [
            'type' => $type,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_show', methods: ['GET'])]
    public function show(Type $type): Response
    {
        return $this->render('back/type/show.html.twig', [
            'type' => $type,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Type $type, TypeRepository $typeRepository): Response
    {
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeRepository->save($type, true);

            return $this->redirectToRoute('back_app_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/type/edit.html.twig', [
            'type' => $type,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_delete', methods: ['POST'])]
    public function delete(Request $request, Type $type, TypeRepository $typeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$type->getId(), $request->request->get('_token'))) {
            $typeRepository->remove($type, true);
        }

        return $this->redirectToRoute('back_app_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
