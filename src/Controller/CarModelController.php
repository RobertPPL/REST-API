<?php

namespace App\Controller;

use App\Entity\CarModel;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Repository\{CarBrandRepository, CarModelRepository};
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

/**
* @Route("/api/v1")
*/
class CarModelController extends AbstractController
{
    
    private ValidatorInterface $validator;
    private ManagerRegistry $doctrine;
    private CarModelRepository $carModelRepository;
    private CarBrandRepository $carBrandRepository;

    public function __construct(
        ValidatorInterface $validator,
        ManagerRegistry $doctrine,
        CarModelRepository $carModelRepository,
        CarBrandRepository $carBrandRepository
        )
    {
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->carModelRepository = $carModelRepository;
        $this->carBrandRepository = $carBrandRepository;
    }
    
    /**
     * @Route("/car/model", name="app_car_model", methods={"get"})
     */
    public function getCarsList(SerializerInterface $serializer): Response
    {
        $car_models = $this->carModelRepository->findAll();
        $json = $serializer->serialize(
            $car_models,
            'json', ['groups' => ['car', 'model']]
        );

        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/car/model", name="app_car_model_create", methods={"post"})
     */
    public function createCar(Request $request): Response
    {
        $brand = $this->carBrandRepository->find($request->get('brand_id'));

        $car_model = new CarModel();
        $car_model->setCarModel($request->get('car_model'));
        $car_model->setBrand($brand);
        $errors = $this->validator->validate($car_model);

        $alreadyExists = $this->carModelRepository->findBy(
            ['car_model' => $request->get('car_model')],
            null,
            1
        );

        if(count($errors) > 0 || $alreadyExists) {
            throw new BadRequestException();
        }

        $this->carModelRepository->add($car_model, true);

        return new Response('', 201);
    }

    /**
     * @Route("/car/model/{car_model}", name="app_car_model_read", methods={"get"})
     */
    public function getCarById(CarModel $car_model): JsonResponse
    {
        return $this->json($car_model);
    }

    /**
     * @Route("/car/model/{car_model}", name="app_car_model_update", methods={"patch"})
     */
    public function updateCar(Request $request, CarModel $car_model): Response
    {
        $car_model->setCarModel($request->get('car_name'));
        $errors = $this->validator->validate($car_model);

        if(count($errors) > 0) {
            throw new BadRequestException();
        }

        $entityManager = $this->doctrine->getManager();

        $entityManager->persist($car_model);
        $entityManager->flush();
        
        return new Response();
    }

    /**
     * @Route("/car/model/{car_model}", name="app_car_model_delete", methods={"delete"})
     */
    public function deleteCar(CarModel $car_model): Response
    {
        $this->carModelRepository->remove($car_model, true);

        return new Response();
    }
}
