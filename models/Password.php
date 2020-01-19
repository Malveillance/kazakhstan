<?php

namespace app\models;

use Yii;

class Password extends \yii\base\Model
{
    public $current;
    public $new;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['current', 'required', 'message' => Yii::t('app', 'Необходимо ввести текущий пароль')],
            ['new', 'required', 'message' => Yii::t('app', 'Необходимо ввести новый пароль')],
            ['new', 'compare', 'compareAttribute' => 'current', 'operator' => '!=', 'message' => Yii::t('app', 'Введенные пароли не должны совпадать')],
            ['current', 'validateCurrent'],
        ];
    }

    /**
     * Validates the current password.
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateCurrent($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;

            if (!$user->validatePassword($this->current)) {
                $this->addError($attribute, Yii::t('app', 'Вы указали неверный пароль'));
            }
        }
    }

    /**
     * Change password.
     * @return bool whether the password is changed in successfully
     */
    public function change()
    {
        if ($this->validate()) {
            $user = Yii::$app->user->identity;

            $user->setPassword($this->new);
            $user->update();

            Yii::$app->session->setFlash('success', Yii::t('yii', 'Пароль успешно изменен.'));

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'current' => Yii::t('app', 'Текущий пароль'),
            'new' => Yii::t('app', 'Новый пароль'),
        ];
    }
}
