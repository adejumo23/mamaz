<?php


namespace Application\Model\Entity;


use App\Model\Entity\AbstractEntity;

class Cart extends AbstractEntity implements \JsonSerializable
{

    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $userId;
    /**
     * @var mixed
     */
    public $formData = [];
    /**
     * @var float
     */
    public $price;

    public function preSaveHook()
    {
        if (is_array($this->formData)) {
            $this->formData = json_encode($this->formData);
        }
    }

    public function postFetchHook()
    {
        if (is_string($this->formData)) {
            $this->formData = json_decode($this->formData, true);
        }
    }
    public function getMetadata()
    {
        $this->setRepositoryName(\Application\Model\Repository\Cart::class);
        $this->setTable('cart');
        $this->setField('id', 'int', null, true);
        $this->setField('user_id', null, 'userId');
        $this->setField('formdata', null, 'formData');
        $this->setField('price', 'float', 'price');
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Cart
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return Cart
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return array
     */
    public function getFormData()
    {
        return $this->formData;
    }

    /**
     * @param array $formData
     * @return Cart
     */
    public function setFormData($formData)
    {
        $this->formData = $formData;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'formData' => $this->formData,
            'price' => $this->price,
        ];
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Cart
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
}