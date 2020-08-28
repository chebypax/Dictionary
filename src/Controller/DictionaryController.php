<?php

namespace App\Controller;

use App\Entity\GraphEntity;
use App\Entity\TaskEntity;
use App\Form\TaskType;
use App\Service\DataConverter;
use App\Service\DictionaryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DictionaryController extends AbstractController
{
    /**
     * @var DataConverter
     */
    private $dataConverter;
    /**
     * @var DictionaryManager
     */
    private $dictionaryManager;

    public function __construct(DataConverter $dataConverter, DictionaryManager $dictionaryManager)
    {
        $this->dataConverter = $dataConverter;
        $this->dictionaryManager = $dictionaryManager;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $task = new TaskEntity();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TaskEntity $task */
            $task = $form->getData();

            $wordInput1 = $task->getFirstWord();
            $wordInput2 = $task->getSecondWord();
            $error = null;

            if ($wordInput1 === $wordInput2) {
                $error = 'Введены одинаковые слова';
            }
            if (strlen($wordInput1) !== strlen($wordInput2)) {
                $error = 'Слова разной длины';
            }

            $this->dictionaryManager->setModifiedDictionary(mb_strlen($wordInput1));
            $word1 = $this->dataConverter->convertWordIntoInt($wordInput1);
            $word2 = $this->dataConverter->convertWordIntoInt($wordInput2);

            if (strlen($wordInput1) !== strlen($word1) || strlen($wordInput2) !== strlen($word2)) {
                $error = 'Слова должны быть на русском языке!';
            }

            if (null === $error) {
                $dictionary = $this->dictionaryManager->getModifiedDictionary();
                $start = new \DateTime();
                $graph = new GraphEntity($dictionary);

                $graphTime = new \DateTime();
                $time['graph'] = $graphTime->getTimestamp() - $start->getTimestamp();

                $shortestWay = $graph->shortestWay($word1, $word2);
                $result = [];
                foreach ($shortestWay as $item) {
                    $result[] = $this->dataConverter->convertIntIntoWord($item);
                }
                $shortestWayTime = new \DateTime();
                $time['shortest_way_time'] = $shortestWayTime->getTimestamp() - $graphTime->getTimestamp();

                if (empty($result)) {
                    $result[] = 'Решения задачи нет';
                }
            }
        }

        return $this->render('dictionary/index.html.twig', [
            'form' => $form->createView(),
            'error' => $error ?? null,
            'result' => $result ?? null,
            'time' => $time ?? null
        ]);
    }
}
