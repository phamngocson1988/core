<?php
namespace api\models;

use common\models\Customer as BaseCustomer;

class Customer extends BaseCustomer
{
	public function exportData()
    {
    	$data = [
    		'id' => $this->id,
    		'name' => $this->name,
    		'username' => $this->username,
    		'avatar' => $this->avatar,
    		'email' => $this->email,
    		'status' => $this->status,
    		'created_at' => $this->created_at,
    		'updated_at' => $this->updated_at,
    	];
    	return (object)$data;
    }
}
