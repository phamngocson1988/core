<?php
namespace api\forms;

use Yii;
use yii\base\Model;
use api\models\User;

class LoginForm extends Model
{
    public $username;
    public $password;
 
    private $_user;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'validateStatus'],
            ['password', 'validatePassword'],
        ];
    }

    public function validateStatus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user) {
                if ($user->status == User::STATUS_INACTIVE) {
                    $this->addError('username', Yii::t('frontend', 'customer_is_not_active'));
                    return false;    
                } elseif ($user->status == User::STATUS_DELETED) {
                    $this->addError('username', Yii::t('frontend', 'customer_is_deleted'));
                    return false;    
                }
            }
        }
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $key = base64_decode(Yii::$app->request->cookieValidationKey);
            $tokenId = base64_encode(random_bytes(32));
            $issuedAt = time();
            $notBefore = $issuedAt + 5;
            $expire = $notBefore + 86400;

            $data = [
                'iss' => 'api.kinggems.us',
                'iat' => $issuedAt,
                'jti' => $tokenId,
                'nbf' => $notBefore,
                'exp' => $expire,
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                ]
            ];
            $jwt = \Firebase\JWT\JWT::encode($data, $key,'HS256');
            $user->access_token = $jwt;
            $user->save();
            return $jwt;
        } else {
            return false;
        }
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
