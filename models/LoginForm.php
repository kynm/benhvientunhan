<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm là model được sử dụng để thu thập dữ liệu form đăng nhập.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * Phương thức này được gọi bởi rule 'password' trên.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the parameters given for the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            
            // Kiểm tra xem user có tồn tại và password có hợp lệ không
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Tên đăng nhập hoặc mật khẩu không đúng.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            // Đăng nhập user, sử dụng thời gian cookie 3600*24*30 (30 ngày)
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === false) {
            // Lấy user từ database bằng username
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}