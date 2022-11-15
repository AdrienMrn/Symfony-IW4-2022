<?php

namespace App\Controller\Front;

use App\Entity\Statistic;
use App\Form\StatisticType;
use App\Repository\StatisticRepository;
use App\Security\UserVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route(path: '/pokemon/list', name: 'pokemon_list')]
    public function pokemonList(): Response
    {
        return $this->render('front/user/pokemonList.html.twig', [
            'pokemonList' => $this->getUser()->getPokemons()
        ]);
    }

    #[Route(path: '/pokemon/list/add', name: 'pokemon_list_add')]
    public function pokemonListAdd(Request $request, StatisticRepository $statisticRepository): Response
    {
        $pokemon = new Statistic();
        $form = $this->createForm(StatisticType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $statisticRepository->save($pokemon, $this->getUser(), true);

            return $this->redirectToRoute('front_user_pokemon_list');
        }

        return $this->render('front/user/pokemonListAdd.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/pokemon/list/edit/{id}', name: 'pokemon_list_edit')]
    #[Security("is_granted('ROLE_ADMIN') or statistic.getOwner() === user")]
    // Without voter OR with voter
    #[Security("is_granted('view', statistic)")]
    public function pokemonListEdit(Statistic $statistic, Request $request, StatisticRepository $statisticRepository): Response
    {
        //$this->denyAccessUnlessGranted(UserVoter::VIEW, $statistic);

        $form = $this->createForm(StatisticType::class, $statistic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $statisticRepository->save($statistic, $this->getUser(), true);

            return $this->redirectToRoute('front_user_pokemon_list');
        }

        return $this->render('front/user/pokemonListEdit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
