<?php

namespace app\models;

use Yii;

/**
 * Login is the model behind the login form.
 * @property User|null $user this property is read-only
 */
class Login extends \yii\base\Model
{
    public $username;
    public $password;
    public $remember_me = false;

    private $_user = false;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'required', 'message' => Yii::t('app', 'Необходимо ввести логин')],
            ['password', 'required', 'message' => Yii::t('app', 'Необходимо ввести пароль')],
            ['password', 'validatePassword'],
            ['remember_me', 'boolean'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Вы указали неверный логин или пароль'));
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
            return Yii::$app->user->login($this->getUser(), $this->remember_me ? 3600*24*30 : 0);
        }

        return false;
    }

    /**
     * Finds user by username.
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Логин'),
            'password' => Yii::t('app', 'Пароль'),
            'remember_me' => Yii::t('app', 'Запомнить меня'),
        ];
    }
}
