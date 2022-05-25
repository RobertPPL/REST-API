<?php

namespace App\Controller;

use App\Entity\CarBrand;
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
class CarBrandController extends AbstractController
{
    private ValidatorInterface $validator;
    private ManagerRegistry $doctrine;
    private CarBrandRepository $carBrandRepository;
    private CarModelRepository $carModelRepository;

    public function __construct(
        ValidatorInterface $validator,
        ManagerRegistry $doctrine,
        CarBrandRepository $carBrandRepository,
        CarModelRepository $carModelRepository
        )
    {
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->carBrandRepository = $carBrandRepository;
        $this->carModelRepository = $carModelRepository;
    }

    /**
     * @Route("/car/brand", name="app_car_brand", methods={"get"})
     */
    public function getBrandsList(SerializerInterface $serializer): Response
    {
        $car_brands = $this->carBrandRepository->findAll();
        $json = $serializer->serialize(
            $car_brands,
            'json', ['groups' => ['brand', 'car']]
        );
        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/car/brand", name="app_car_brand_create", methods={"post"})
     */
    public function createBrand(Request $request): Response
    {
        $brand = new CarBrand();
        $brand->setBrandName($request->get('brand_name'));

        $errors = $this->validator->validate($brand);

        $alrearyExists =$this->carBrandRepository->findBy(
            ['brand_name' => $request->get('brand_name')],
            null,
            1
        );

        if(count($errors) > 0 || $alrearyExists) {
            throw new BadRequestException();
        }

        $this->carBrandRepository->add($brand, true);

        return new Response('', 201, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/car/brand/{car_brand}", name="app_car_brand_read", methods={"get"})
     */
    public function getBrandById(CarBrand $car_brand): JsonResponse
    {
        return $this->json($car_brand);
    }

    /**
     * @Route("/car/brand/{brand}", name="app_car_brand_update", methods={"patch"})
     */
    public function updateBrand(Request $request, CarBrand $brand): Response
    {
        $brand->setBrandName($request->get('brand_name'));
        $errors = $this->validator->validate($brand);

        if(count($errors) > 0) {
            throw new BadRequestException();
        }

        $entityManager = $this->doctrine->getManager();

        $entityManager->persist($brand);
        $entityManager->flush();
        
        return new Response();
    }

    /**
     * @Route("/car/brand/{brand}", name="app_car_brand_delete", methods={"delete"})
     */
    public function deleteBrand(CarBrand $brand): Response
    {
        $this->carBrandRepository->remove($brand, true);

        return new Response();
    }

    /**
     * @Route("/car/brand/dettachCar/{brand}", name="app_car_brand_dettach_car", methods={"patch"})
     */
    public function dettachCarFromBrand(Request $request, CarBrand $brand): Response
    {
        $car = $this->carModelRepository->find($request->get('car_model'));

        if(is_null($brand)) {
            throw new NotFoundHttpException();
        }

        $brand->removeCar($car);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($brand);
        $entityManager->persist($car);
        $entityManager->flush();

        return new Response();
    }

    /**
     * @Route("/car/brand/attachCar/{brand}", name="app_car_brand_attach_car", methods={"patch"})
     */
    public function attachCarToBrand(Request $request, CarBrand $brand): Response
    {
        $car = $this->carModelRepository->find($request->get('car_model'));

        if(is_null($brand)) {
            throw new NotFoundHttpException();
        }

        $car->setBrand($brand);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($car);
        $entityManager->flush();

        return new Response();
    }
}
