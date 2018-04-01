<?php

namespace Plugin\Track\Model;

class User extends AbstractModel implements Deserializable, Serializable {

    private $username = null;
    private $hash = null;
    private $email = null;
    private $resetSecret = null;
    private $resetTime = null;
    private $createdAt = null;
    private $lastActiveAt = null;
    private $isDeleted = false;
    private $deletedAt = null;
    private $isVerified = null;
    private $verifiedAt = null;

    private $grooaUid = 0;
    private $firstName = null;
    private $lastName = null;
    private $isBusinessAccount = false;
    private $companyName = null;

    public static function deserialize(array $serialized): ?User
    {
        $user = new User();

        if (isset($serialized['id'])) {
            $user->setId($serialized['id']);
        }

        if (isset($serialized['username'])) {
            $user->setUsername($serialized['username']);
        }

        if (isset($serialized['hash'])) {
            $user->setHash($serialized['hash']);
        }

        if (isset($serialized['email'])) {
            $user->setEmail($serialized['email']);
        }

        if (isset($serialized['resetSecret'])) {
            $user->setResetSecret($serialized['resetSecret']);
        }

        if (isset($serialized['resetTime'])) {
            $user->setResetTime($serialized['resetTime']);
        }

        if (isset($serialized['createdAt'])) {
            $user->setCreatedAt($serialized['createdAt']);
        }

        if (isset($serialized['lastActiveAt'])) {
            $user->setLastActiveAt($serialized['lastActiveAt']);
        }

        if (isset($serialized['isDeleted'])) {
            $user->setIsDeleted($serialized['isDeleted'] === '1' ? true : false);
        }

        if (isset($serialized['deletedAt'])) {
            $user->setDeletedAt($serialized['deletedAt']);
        }

        if (isset($serialized['isVerified'])) {
            $user->setIsVerified($serialized['isVerified']);
        }

        if (isset($serialized['verifiedAt'])) {
            $user->setVerifiedAt($serialized['verifiedAt']);
        }

        if (isset($serialized['grooaUid'])) {
            $user->setGrooaUid($serialized['grooaUid']);
        }

        if (isset($serialized['firstName'])) {
            $user->setFirstName($serialized['firstName']);
        }

        if (isset($serialized['lastName'])) {
            $user->setLastName($serialized['lastName']);
        }

        if (isset($serialized['businessAccount'])) {
            $user->setIsBusinessAccount($serialized['businessAccount']);
        }

        if (isset($serialized['companyName'])) {
            $user->setCompanyName($serialized['companyName']);
        }

        return $user;
    }

    public function serialize(): array
    {
        $serialized = [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'createdAt' => $this->getCreatedAt(),
            'lastActiveAt' => $this->getLastActiveAt(),
            'isDeleted' => $this->isDeleted(),
            'deletedAt' => $this->getDeletedAt(),
            'isVerified' => $this->isVerified,
            'verifiedAt' => $this->getVerifiedAt(),
            'grooaUid' => $this->getGrooaUid(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'isBusinessAccount' => $this->isBusinessAccount(),
            'companyName' => $this->getCompanyName()
        ];

        return $serialized;
    }

    /**
     * @return null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param null $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return null
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param null $hash
     */
    public function setHash($hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return null
     */
    public function getResetSecret()
    {
        return $this->resetSecret;
    }

    /**
     * @param null $resetSecret
     */
    public function setResetSecret($resetSecret): void
    {
        $this->resetSecret = $resetSecret;
    }

    /**
     * @return null
     */
    public function getResetTime()
    {
        return $this->resetTime;
    }

    /**
     * @param null $resetTime
     */
    public function setResetTime($resetTime): void
    {
        $this->resetTime = $resetTime;
    }

    /**
     * @return null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param null $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return null
     */
    public function getLastActiveAt()
    {
        return $this->lastActiveAt;
    }

    /**
     * @param null $lastActiveAt
     */
    public function setLastActiveAt($lastActiveAt): void
    {
        $this->lastActiveAt = $lastActiveAt;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     */
    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param null $deletedAt
     */
    public function setDeletedAt($deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return null
     */
    public function getisVerified()
    {
        return $this->isVerified;
    }

    /**
     * @param null $isVerified
     */
    public function setIsVerified($isVerified): void
    {
        $this->isVerified = $isVerified;
    }

    /**
     * @return null
     */
    public function getVerifiedAt()
    {
        return $this->verifiedAt;
    }

    /**
     * @param null $verifiedAt
     */
    public function setVerifiedAt($verifiedAt): void
    {
        $this->verifiedAt = $verifiedAt;
    }

    /**
     * @return int
     */
    public function getGrooaUid(): int
    {
        return $this->grooaUid;
    }

    /**
     * @param int $grooaUid
     */
    public function setGrooaUid(int $grooaUid): void
    {
        $this->grooaUid = $grooaUid;
    }

    /**
     * @return null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param null $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param null $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return bool
     */
    public function isBusinessAccount(): bool
    {
        return $this->isBusinessAccount;
    }

    /**
     * @param bool $isBusinessAccount
     */
    public function setIsBusinessAccount(bool $isBusinessAccount): void
    {
        $this->isBusinessAccount = $isBusinessAccount;
    }

    /**
     * @return null
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param null $companyName
     */
    public function setCompanyName($companyName): void
    {
        $this->companyName = $companyName;
    }


}
