<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\StudentFormType;
use App\Entity\Student;

class StudentController extends Controller
{
    /**
     * @Route("/student", name="student")
     */
    public function index()
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

     /**
     * @Route("/add-student", name="add_student")
     */
    public function addStudent(Request $request): Response
    {

        $student = new Student();
        $form = $this->createForm(StudentFormType::class, $student);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();
        }


        return $this->render("student/form.html.twig", [
            "form_title" => "Ajouter un student",
            "form_student" => $form->createView(),
        ]);
    }

/**
 * @Route("/students", name="students")
 */
public function students()
{
    $students = $this->getDoctrine()->getRepository(Student::class)->findAll();

    return $this->render('student/students.html.twig', [
        "students" => $students,
    ]);
}

/**
 * @Route("/update-student/{id}", name="update_student")
 */
public function updateStudent(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $student = $entityManager->getRepository(Student::class)->find($id);
    $form = $this->createForm(StudentFormType::class, $student);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $entityManager->flush();
    }

    return $this->render("student/form.html.twig", [
        "form_title" => "Modifier un student",
        "form_student" => $form->createView(),
    ]);
}

/**
 * @Route("/delete-student/{id}", name="delete_student")
 */
public function deleteProduct(int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $student = $entityManager->getRepository(Student::class)->find($id);
    $entityManager->remove($student);
    $entityManager->flush();

    return $this->redirectToRoute("students");
}
}
