<?php

namespace App\Controller\Back;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pokedex', name: 'pokemon_')]
class PokemonController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(PokemonRepository $pokemonRepository): Response
    {
        return $this->render('back/pokemon/index.html.twig', [
            'pokemons' => $pokemonRepository->findAll()
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, PokemonRepository $pokemonRepository): Response
    {
        $pokemon = new Pokemon();
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pokemonRepository->save($pokemon, true);

            return $this->redirectToRoute('back_pokemon_show', [
               'id' => $pokemon->getId()
            ]);
        }

        return $this->render('back/pokemon/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Pokemon $pokemon): Response
    {
        return $this->render('back/pokemon/show.html.twig', [
            'pokemon' => $pokemon,
            'text' => 'test <br> test2 <script>alert()</script>'
        ]);
    }

    #[Route('/{id}/update', name: 'update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(Pokemon $pokemon, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $managerRegistry->getManager()->flush();

            return $this->redirectToRoute('back_pokemon_show', [
                'id' => $pokemon->getId()
            ]);
        }

        return $this->render('back/pokemon/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/remove/{token}', name: 'remove', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function remove(Pokemon $pokemon, string $token, PokemonRepository $pokemonRepository): Response
    {
        if (!$this->isCsrfTokenValid('remove' . $pokemon->getId(), $token)) {
            throw $this->createAccessDeniedException();
        }

        $pokemonRepository->remove($pokemon, true);

        return $this->redirectToRoute('back_pokemon_index');
    }
}
