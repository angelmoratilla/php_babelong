<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Word;
use App\Entity\Wordset;
use App\Entity\WordsetWords;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
class UserAreaController extends AbstractController
{
    #[Route('/dashboard', name: 'app_user_dashboard')]
    public function dashboard(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/vocabulary', name: 'app_user_vocabulary')]
    public function vocabulary(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('user/vocabulary.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/exercises', name: 'app_user_exercises')]
    public function exercises(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('user/exercises.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/test', name: 'app_user_test')]
    public function test(EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $hskLevel = $user->getHsklevel() ?? 1;

        // Buscar el wordset correspondiente al nivel HSK del usuario
        $wordsetName = 'HSK' . $hskLevel;
        
        // Contar palabras disponibles en ese wordset
        $totalWords = $entityManager->createQueryBuilder()
            ->select('COUNT(DISTINCT w.id)')
            ->from(Word::class, 'w')
            ->innerJoin('App\Entity\Wordset', 'ws', 'WITH', '1=1')
            ->where('ws.name = :wordsetName')
            ->andWhere('w.translation1 IS NOT NULL')
            ->andWhere('w.simplified IS NOT NULL')
            ->andWhere('w.realPinyin IS NOT NULL')
            ->andWhere('EXISTS (SELECT 1 FROM App\Entity\WordsetWords ww WHERE ww.wordId = w.id AND ww.wordsetId = ws.id)')
            ->setParameter('wordsetName', $wordsetName)
            ->getQuery()
            ->getSingleScalarResult();

        if ($totalWords < 4) {
            $this->addFlash('error', 'No hay suficientes palabras en tu nivel HSK para realizar el test.');
            return $this->redirectToRoute('app_user_dashboard');
        }

        return $this->render('user/test.html.twig', [
            'user' => $user,
            'hskLevel' => $hskLevel,
            'totalWords' => $totalWords,
        ]);
    }

    #[Route('/test/next-question', name: 'app_user_test_next_question', methods: ['GET'])]
    public function nextQuestion(EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $hskLevel = $user->getHsklevel() ?? 1;
        $wordsetName = 'HSK' . $hskLevel;

        // Obtener una palabra aleatoria del wordset
        $connection = $entityManager->getConnection();
        $sql = "
            SELECT w.* 
            FROM word w
            INNER JOIN wordset_words ww ON w.id = ww.word_id
            INNER JOIN wordset ws ON ww.wordset_id = ws.id
            WHERE ws.name = :wordsetName
            AND w.translation1 IS NOT NULL
            AND w.simplified IS NOT NULL
            AND w.real_pinyin IS NOT NULL
            ORDER BY RAND()
            LIMIT 1
        ";
        
        $stmt = $connection->prepare($sql);
        $result = $stmt->executeQuery(['wordsetName' => $wordsetName]);
        $correctWordData = $result->fetchAssociative();

        if (!$correctWordData) {
            return new JsonResponse(['error' => 'No se encontraron palabras para tu nivel'], 404);
        }

        // Obtener 3 palabras incorrectas del mismo wordset
        $sql = "
            SELECT w.* 
            FROM word w
            INNER JOIN wordset_words ww ON w.id = ww.word_id
            INNER JOIN wordset ws ON ww.wordset_id = ws.id
            WHERE ws.name = :wordsetName
            AND w.translation1 IS NOT NULL
            AND w.id != :correctId
            ORDER BY RAND()
            LIMIT 3
        ";
        
        $stmt = $connection->prepare($sql);
        $result = $stmt->executeQuery([
            'wordsetName' => $wordsetName,
            'correctId' => $correctWordData['id']
        ]);
        $wrongWordsData = $result->fetchAllAssociative();

        // Crear array de opciones
        $options = [];
        foreach ($wrongWordsData as $wordData) {
            $options[] = [
                'id' => $wordData['id'],
                'translation' => $wordData['translation1'],
                'correct' => false
            ];
        }

        // Agregar la respuesta correcta
        $options[] = [
            'id' => $correctWordData['id'],
            'translation' => $correctWordData['translation1'],
            'correct' => true
        ];

        // Mezclar las opciones
        shuffle($options);

        return new JsonResponse([
            'question' => [
                'id' => $correctWordData['id'],
                'simplified' => $correctWordData['simplified'],
                'pinyin' => $correctWordData['real_pinyin'],
            ],
            'options' => $options,
            'correctAnswer' => $correctWordData['translation1']
        ]);
    }

    #[Route('/test/check-answer', name: 'app_user_test_check_answer', methods: ['POST'])]
    public function checkAnswer(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $selectedId = $data['selectedId'] ?? null;
        $questionId = $data['questionId'] ?? null;

        if (!$selectedId || !$questionId) {
            return new JsonResponse(['error' => 'Datos incompletos'], 400);
        }

        $wordRepository = $entityManager->getRepository(Word::class);
        $correctWord = $wordRepository->find($questionId);

        if (!$correctWord) {
            return new JsonResponse(['error' => 'Palabra no encontrada'], 404);
        }

        $isCorrect = ($selectedId == $questionId);

        return new JsonResponse([
            'correct' => $isCorrect,
            'correctAnswer' => $correctWord->getTranslation1(),
            'explanation' => sprintf(
                '%s (%s) significa: %s',
                $correctWord->getSimplified(),
                $correctWord->getRealPinyin(),
                $correctWord->getTranslation1()
            )
        ]);
    }

    #[Route('/progress', name: 'app_user_progress')]
    public function progress(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('user/progress.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile', name: 'app_user_profile')]
    public function profile(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
}
