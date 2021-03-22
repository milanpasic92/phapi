<?php

namespace Phapi\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class AdminUser extends BaseModel
{

    private int $id;
    private string $role;
    private string $login;
    private string $email;
    private ?string $name;
    private ?string $password;
    private int $active = 1;
    private int $fk_school_id;
    private ?string $magic_link;
    private int $accepted_gdpr;

    public static array $roles = [
        'professor' => 'professor',
        'admin' => 'Admin',
        'super_admin' => 'Super Admin',
    ];

    public function initialize()
    {
        $this->setSource("admin_user");
    }

    public function validation()
    {
        $validator = new Validation();

        $validator->add('login', new PresenceOf([
            'message' => $this->getDi()->get('helper')->translate("login_required")
        ]));

        $validator->add('login', new UniquenessValidator([
            "message" => $this->getDi()->get('helper')->translate("login_must_be_unique")
        ]));

        $validator->add('email', new Validation\Validator\Email([
            'message' => $this->getDi()->get('helper')->translate("email_not_valid")
        ]));

        $validator->add('password', new PresenceOf([
            'message' => $this->getDi()->get('helper')->translate("password_required")
        ]));


        return $this->validate($validator);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AdminUser
     */
    public function setId(int $id): AdminUser
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return AdminUser
     */
    public function setRole(string $role): AdminUser
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return AdminUser
     */
    public function setLogin(string $login): AdminUser
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return AdminUser
     */
    public function setEmail(string $email): AdminUser
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return AdminUser
     */
    public function setName(?string $name): AdminUser
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param null|string $password
     * @return AdminUser
     */
    public function setPassword(?string $password): AdminUser
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return int
     */
    public function getActive(): int
    {
        return $this->active;
    }

    /**
     * @param int $active
     * @return AdminUser
     */
    public function setActive(int $active): AdminUser
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return int
     */
    public function getFkSchoolId(): int
    {
        return $this->fk_school_id;
    }

    /**
     * @param int $fk_school_id
     * @return AdminUser
     */
    public function setFkSchoolId(int $fk_school_id): AdminUser
    {
        $this->fk_school_id = $fk_school_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getMagicLink(): ?string
    {
        return $this->magic_link;
    }

    /**
     * @param null|string $magic_link
     * @return AdminUser
     */
    public function setMagicLink(?string $magic_link): AdminUser
    {
        $this->magic_link = $magic_link;
        return $this;
    }

    /**
     * @return int
     */
    public function getAcceptedGdpr(): int
    {
        return $this->accepted_gdpr;
    }

    /**
     * @param int $accepted_gdpr
     * @return AdminUser
     */
    public function setAcceptedGdpr(int $accepted_gdpr): AdminUser
    {
        $this->accepted_gdpr = $accepted_gdpr;
        return $this;
    }
}
