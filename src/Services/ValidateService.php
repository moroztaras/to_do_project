<?php

namespace App\Services;

use App\Exception\JsonHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidateService
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $object
     */
    public function validate($object)
    {
        $errors = $this->validator->validate($object);
        if (count($errors))
            throw new JsonHttpException(400, $errors->get(0)->getMessage());
    }
}
