<?php
namespace core\forms;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use core\repositories\Users;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $password_confirm;

    /**
     * @var \core\repositories\Users
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Password reset token cannot be blank.');
        }

        $this->_user = Users::findByResetToken($token, (string)Users::STATUS_ACTIVE);
        if (empty($this->_user)) {
            $this->_user = Users::findByResetToken($token, (string)Users::STATUS_WAIT);
        }
        if (!$this->_user) {
            throw new InvalidArgumentException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'password_confirm'], 'required'],
            [['password', 'password_confirm'], 'string', 'min' => 6, 'message' => Yii::t('Users', 'ERROR_MIN_CHAR_PASSWORD_CONFIRM_EXISTS')],
            [['password', 'password_confirm'], 'string', 'max' => 80, 'message' => Yii::t('Users', 'ERROR_MAX_CHAR_PASSWORD_CONFIRM_EXISTS')],
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],
            [['password', 'password_confirm'], 'match', 'pattern' => '#\d.*\d#s', 'message' => Yii::t('Users', 'ERROR_MIN_MAX_2CHAR_2NUM')],
            [['password', 'password_confirm'], 'match', 'pattern' => '#[a-z].*[a-z]#is', 'message' => Yii::t('Users', 'ERROR_MIN_MAX_2CHAR_2NUM')],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);

        if ($user->status == Users::STATUS_WAIT) {
            $user->getService()->sendMailByCreateUser();
        } else {
            $user->removeResetToken();
        }

        return $user->save(false);
    }
}
