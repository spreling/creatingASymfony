<?php
/**
 * MainController.php
 *
 * This file contains the MainController class
 *
 * @package AppBundle\Controller
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Evaluation;
use AppBundle\Form\Type\TenChoiceQuestionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController - The class render the main page
 *
 * This Controller show depending on the route request the main page
 * The main page contains the dynamic generated evaluation form
 *
 * @author Spreling - Harm Jacob Drijfhout Email: Spreling@gmail.com
 * @version 1.0
 * @since 1.0
 * @copyright Spreling
 * @license MIT
 * @abstract This class renders the main page
 * @package AppBundle\Controller
 */
class MainController extends Controller
{
    /**
     * showAction - function which generator the main page
     *
     * This method is the default methode which is used when visiting the main page.
     *
     * @access public
     * @since 1.0
     * @version 1.5
     * @Route("/")
     *
     * @param Request|null $request
     * @return Response
     * @throws \Twig_Error
     */
    public function showAction(Request $request)
    {
        //Get evaluation data
        $evaluation = $this->getEvaluation(9); //todo for now it uses a fixed evaluation number


        /*
        * Ik heb geprobberd het formulier middels het active record systeem van symfony te genereren maar dat is
        * niet gelukt. Niet in deze korte tijd. De regel hieronder werkt alleen precies andersom. inplaats van het weergeven
        * van de vragen met de antwoordveld. kan je de vragen zelf aanpassen. voor nu heb ik daarom een handmatig formulier
        * gebouwd.
        */
        //$evaluation = new Evaluation();
        //$evaluation->setQuestions($questions);
        //$form = $this->createForm(QuestionSetType::class, $evaluation);


        //manual form building
        $form = $this->createFormBuilder();
        //inserting questions for the database in the form
        foreach ($evaluation->getQuestions() as $question) {
            $form->add('selfQuestions_' . $question->getId(),
                TenChoiceQuestionType::class, array(
                    'label' => $question->getQuestion()
                ));
        }
        $form = $form->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database
            echo "yaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";

        }
        //building template
        return new Response($this->container->get('templating')->render(
            'main/main.html.twig',
            array(
                'form' => $form->createView()
            )
        ));
    }

    /**
     * @param $id
     * @return Evaluation
     */
    private function getEvaluation($id)
    {
        return $this->getDoctrine()
            ->getRepository('AppBundle:Evaluation')
            ->find(9); //todo for now it uses a fixed evaluation number
    }
}
