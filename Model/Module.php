<?php

namespace Plugin\Track\Model;

use Ip\Exception;
use Plugin\Track\Repository\VideoRepository;

class Module extends AbstractModel
{
    const TABLE = 'track';
    const GROOA_COURSE_TABLE = 'grooa_course';

    private $title;
    private $shortDescription = null;
    private $longDescription = null;
    private $createdOn;
    private $thumbnail = null;
    private $largeThumbnail = null;
    private $price = 0;
    private $isFree = false;
    private $state = 'draft';
    private $order = 0;

    private $type = 'module';
    private $num = null; // The course-number

    private $courseId = null;

    public function __construct()
    {
        $this->createdOn = date("Y-m-d H:i:s");
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @param mixed $shortDescription
     */
    public function setShortDescription($shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return mixed
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * @param mixed $longDescription
     */
    public function setLongDescription($longDescription): void
    {
        $this->longDescription = $longDescription;
    }

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param mixed $createdOn
     */
    public function setCreatedOn($createdOn): void
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param mixed $thumbnail
     */
    public function setThumbnail($thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return mixed
     */
    public function getLargeThumbnail()
    {
        return $this->largeThumbnail;
    }

    /**
     * @param mixed $largeThumbnail
     */
    public function setLargeThumbnail($largeThumbnail): void
    {
        $this->largeThumbnail = $largeThumbnail;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return bool
     */
    public function isFree(): bool
    {
        return $this->isFree;
    }

    /**
     * @param bool $isFree
     */
    public function setIsFree(bool $isFree): void
    {
        $this->isFree = $isFree;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return null
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * @param null $num
     */
    public function setNum($num): void
    {
        $this->num = $num;
    }

    /**
     * @return null
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * @param null $courseId
     */
    public function setCourseId($courseId): void
    {
        $this->courseId = $courseId;
    }

    /**
     * Converts the module to an associative array,
     * which can be used converted to json
     */
    public function serializeToArray(): array {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'shortDescription' => $this->getShortDescription(),
            'longDescription' => $this->getLongDescription(),
            'createdOn' => $this->getCreatedOn(),
            'thumbnail' => $this->getThumbnail(),
            'cover' => $this->getLargeThumbnail(),
            'price' => $this->getPrice(),
            'state' => $this->getState(),
            'type' => $this->getType(),
            'number' => $this->getNum(),
            'url' => ipConfig()->baseUrl() . "online-courses/" . $this->getId()
        ];
    }

    public static function findAllPublished($courseId = null)
    {
        return self::findAll('published', $courseId);
    }

    public static function findAll($state = null, $courseId = null)
    {
        $were = [];

        if (!empty($state)) {
            if (!in_array($state, ['draft', 'published', 'withdrawn'])) {
                throw new Exception("Unknown Track state: $state");
            }

            $were['state'] = $state;
        }

        if (!empty($courseId)) {
            $were['grooaCourseId'] = $courseId;
        }

        return ipDb()->selectAll(self::TABLE, '*', $were, 'ORDER BY `num` ASC');
    }

    public static function isModuleFree($trackId)
    {
        return !empty(ipDb()->selectRow(self::TABLE, 'trackId', ['trackId' => $trackId, 'isFree' => true]));
    }

    public static function get($trackId, $courseId = null)
    {
        if (empty($trackId)) {
            return null;
        }

        $track = ipDb()->selectRow(self::TABLE, '*', ['trackId' => $trackId]);

        if (empty($track)) {
            return null;
        }

        $track['grooaCourse'] = self::getGrooaCourseById($track['grooaCourseId']);
        $track['courseRootUri'] = $track['grooaCourse']['label'];

        if ($courseId == null) {
            $track['courses'] = ipDb()->selectAll(
                VideoRepository::TABLE_NAME,
                '`courseId`, `title`, `shortDescription`, `thumbnail`',
                ['trackId' => $trackId]
            );
        } else {
            $track['course'] = ipDb()->selectRow(
                VideoRepository::TABLE_NAME,
                '*',
                ['trackId' => $trackId, 'courseId' => $courseId]
            );
        }

        return $track;
    }

    public static function getGrooaCourseIdByTrackId($trackId)
    {
        $row = ipDb()->selectRow(self::TABLE, ['grooaCourseId'], ['trackId' => $trackId]);

        return !empty($row) && !empty($row['grooaCourseId']) ? $row['grooaCourseId'] : null;
    }

    public static function getGrooaCourseByLabel($label)
    {
        return ipDb()->selectRow(self::GROOA_COURSE_TABLE, '*', ['label' => $label]);
    }

    public static function getGrooaCourseById($id)
    {
        return ipDb()->selectRow(self::GROOA_COURSE_TABLE, '*', ['id' => $id]);
    }

    public static function getAllLastCreated($limit = 10)
    {
        return ipDb()->selectAll(self::TABLE, '*', [], "ORDER BY `createdOn` DESC LIMIT " . esc($limit) . ";");
    }

    /**
     * Will fetch a list with all the records form the database,
     * in the following format:
     * [
     *  [0, 'Some Title']
     * ]
     *
     * Where 0 is `track_id` and 'Some Title' is `title`
     */
    public static function getWithIdAndTitle()
    {
        $tracks = ipDb()->selectAll('track', '`trackId`, `title`', [], "ORDER BY `createdOn` DESC");

        return array_map(function ($t) {
            return [$t['trackId'], $t['title']];
        }, $tracks);
    }

    public static function getGrooaCourseWithIdAndName()
    {
        $courses = ipDb()->selectAll(self::GROOA_COURSE_TABLE, ['id', 'name'], [], 'ORDER BY `name` DESC');

        return array_map(function ($t) {
            return [$t['id'], $t['name']];
        }, $courses);
    }

    /**
     * Will get the full track data, for
     * a track the user has bought
     */
    public static function getByUser($user)
    {
        if (!user) {
            return null;
        }
    }
}
