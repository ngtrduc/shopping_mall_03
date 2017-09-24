<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\User;
/**
 * @ORM\Entity
 * @ORM\Table(name="activities")
 */
class Activity
{
    const ORDER = 1;
    const COMMENT = 2;
    const REVIEW = 3;
    const ORDER_SHIP = 4;
    const ORDER_COMPLETE = 5;
    
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="activities")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    protected $sender;
    /*
     * Returns associated user.
     * @return \Application\Entity\User
     */
    public function getSender() 
    {
        return $this->sender;
    }
      
    /**
     * Sets associated user.
     * @param \Application\Entity\User $user
     */
    public function setSender($sender) 
    {
        $this->sender = $sender;
        $sender->addActivity($this);
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     */
    protected $receiver;
    /*
     * Returns associated user.
     * @return \Application\Entity\User
     */
    public function getReceiver() 
    {
        return $this->receiver;
    }
      
    /**
     * Sets associated user.
     * @param \Application\Entity\User $user
     */
    public function setReceiver($receiver) 
    {
        $this->receiver = $receiver;
        $receiver->addNotification($this);
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="type")
     */
    protected $type;

    /**
     * @ORM\Column(name="target_id")
     */
    protected $target_id;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $date_created;

    // Returns ID of this post.
    public function getId() 
    {
        return $this->id;
    }

    // Sets ID of this post.
    public function setId($id) 
    {
        $this->id = $id;
    }

    public function getType() 
    {
        return $this->type;
    }

    public function setType($type) 
    {
        $this->type = $type;
    }

    public function getTargetId() 
    {
        return $this->target_id;
    }

    public function setTargetId($target_id) 
    {
        $this->target_id = $target_id;
    }
    public function getDateCreated() 
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created) 
    {
        $this->date_created = $date_created;
    }
}
