<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'L\'email est déjà utilisé.', groups: ['registration'])]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.", groups: ['Default'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true, unique:true)]
    #[Assert\NotBlank(message: "L'email ne peut pas être vide.", groups: ['Default'])]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas un email valide.", groups: ['Entreprise'])]
    #[Assert\Regex(
        pattern: '/^[^@]+@esprit\.tn$/',
        message: "L'email doit se terminer par @esprit.tn.",
        groups: ['Student','Teacher']
    )]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?RoleEnum $role = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le site web ne peut pas être vide.", groups: ['Entreprise'])]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le classe ne peut pas être vide.", groups: ['Student'])]
    private ?string $classe = null;

    #[Assert\Callback(groups: ['Student'])]
    public function validateClasse(ExecutionContextInterface $context): void
    {
    if ($this->classe !== null && $this->niveau !== null) {
        $niveauFirstChar = substr((string) $this->niveau, 0, 1);
        $classeFirstChar = substr($this->classe, 0, 1);

            if ($classeFirstChar !== $niveauFirstChar || strlen($this->classe) < 2) {
                $context->buildViolation('La classe doit commencer par le même chiffre que le niveau')
                        ->atPath('classe')
                        ->addViolation();
            }
        }
    }

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le pays ne peut pas être vide.", groups: ['Entreprise'])]
    private ?string $pays = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "La localisation ne peut pas être vide.", groups: ['Entreprise'])]
    private ?string $localisation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le cin ne peut pas être vide.", groups: ['Teacher','Student'])]
    #[Assert\Length(
        exactMessage: "Le cin doit être de longueur 8.",
        min: 8,
        max: 8,
        groups: ['Teacher', 'Student']
    )]
    #[Assert\Regex(
        pattern: '/^[01]\d{7}$/',
        message: "Le cin doit commencer par 0 ou 1 et contenir 8 chiffres.",
        groups: ['Teacher', 'Student']
    )]
    private ?string $cin = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "Le niveau ne peut pas être vide.", groups: ['Student'])]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'Le niveau doit être entre {{ min }} et {{ max }}.',
        groups: ['Student']
    )]
    private ?int $niveau = null;

    private $entityManager;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le genre ne peut pas être vide.", groups: ['Student','Teacher'])]
    private ?string $genre = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Assert\NotBlank(message: "La date de naissance ne peut pas être vide.", groups: ['Student','Teacher'])]
    private ?\DateTimeImmutable $date_naissance = null;

    #[Assert\Callback(groups: ['Student', 'Teacher'])]
    public function validateDateNaissance(ExecutionContextInterface $context, $payload): void
    {
    
    $latestDate = new \DateTimeImmutable('2006-01-01');

    if ($this->date_naissance !== null && $this->date_naissance > $latestDate) {
        $context->buildViolation('La date de naissance doit être moins de l\'année 2006')
                ->atPath('dateNaissance')
                ->addViolation();
        }  
    }

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profil_picture = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide.", groups: ['Student','Teacher'])]
    private ?string $prenom = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_enabled = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Candidature::class, cascade: ['persist', 'remove'])]
    private Collection $candidatures;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Offre::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $offres;

    #[ORM\OneToMany(mappedBy: 'CLUB_RH', targetEntity: Publication::class, cascade: ['persist', 'remove'])]
    private Collection $publications;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Inscription::class, cascade: ['persist', 'remove'])]
    private Collection $inscriptions;

    #[ORM\OneToMany(mappedBy: 'teacher', targetEntity: Cours::class, cascade: ['persist', 'remove'])]
    private Collection $cours;

    #[ORM\OneToMany(mappedBy: 'teacher', targetEntity: Project::class, cascade: ['persist', 'remove'])]
    private Collection $projects;

    #[ORM\ManyToMany(targetEntity: ProjectMembers::class, mappedBy: 'student', cascade: ['persist', 'remove'])]
    private Collection $projectMembers;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Message::class, cascade: ['persist', 'remove'])]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'uploaded_by', targetEntity: File::class, cascade: ['persist', 'remove'])]
    private Collection $files;

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Events::class, cascade: ['persist', 'remove'])]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: EventReactions::class, cascade: ['persist', 'remove'])]
    private Collection $eventReactions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: EventComments::class, cascade: ['persist', 'remove'])]
    private Collection $eventComments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notifications::class, cascade: ['persist', 'remove'])]
    private Collection $notifications;

    #[ORM\ManyToMany(targetEntity: Events::class, mappedBy: 'participants', cascade: ['persist', 'remove'])]
    private Collection $event_participant;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ResetPasswordToken::class, cascade: ['persist', 'remove'])]
    private Collection $resetPasswordTokens;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="following")
     */
    private $followers;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="followers")
     * @ORM\JoinTable(name="user_follows",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="followed_user_id", referencedColumnName="id")}
     * )
     */
    private $following;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FollowNotification::class)]
    private Collection $followNotifications;

    public function getFollowers(): ?Collection {
        return $this->followers;
    }

    public function getFollowing(): ?Collection {
        return $this->following;
    }

    public function setFollowers(ArrayCollection $followers): void {
        $this->followers = $followers;
    }

    public function setFollowing(ArrayCollection $following): void {
        $this->following = $following;
    }

    public function addFollower(User $user): self {
        if (!$this->followers->contains($user)) {
            $this->followers[] = $user;
            $user->addFollowing($this);
        }

        return $this;
    }

    public function addFollowing(User $user): self {
        if (!$this->following->contains($user)) {
            $this->following[] = $user;
            $user->addFollower($this);
        }

        return $this;
    }

    public function removeFollower(User $user): self {
        if ($this->followers->removeElement($user)) {
            $user->removeFollowing($this);
        }

        return $this;
    }

    public function removeFollowing(User $user): self {
        if ($this->following->removeElement($user)) {
            $user->removeFollower($this);
        }

        return $this;
    }

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
        $this->offres = new ArrayCollection();
        $this->publications = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->projectMembers = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->eventReactions = new ArrayCollection();
        $this->eventComments = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->event_participant = new ArrayCollection();
        $this->resetPasswordTokens = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->followNotifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?RoleEnum
    {
        return $this->role;
    }

    public function setRole(RoleEnum $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(?string $classe): static
    {
        $this->classe = $classe;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(?int $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeImmutable
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(?\DateTimeImmutable $date_naissance): static
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getProfilPicture(): ?string
    {
        return $this->profil_picture;
    }

    public function setProfilPicture(?string $profil_picture): static
    {
        $this->profil_picture = $profil_picture;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->is_enabled;
    }

    public function setIsEnabled(?bool $is_enabled): static
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }


    public function getRoles(): array
    {
        return ['ROLE_' . $this->role->value];
    }
    public function getSalt(): ?string
    {
        return null;
    }

public function eraseCredentials(): void {}

public function getUsername(): string
{
    return $this->email;
}

public function getUserIdentifier(): string
{
    return $this->email;
}







    /**
     * @return Collection<int, candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): static
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setUser($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getUser() === $this) {
                $candidature->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Offre>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): static
    {
        if (!$this->offres->contains($offre)) {
            $this->offres->add($offre);
            $offre->setEntreprise($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): static
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getEntreprise() === $this) {
                $offre->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): static
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
            $publication->setCLUBRH($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): static
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getCLUBRH() === $this) {
                $publication->setCLUBRH(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setEtudiant($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getEtudiant() === $this) {
                $inscription->setEtudiant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): static
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setTeacher($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getTeacher() === $this) {
                $cour->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setTeacher($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getTeacher() === $this) {
                $project->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProjectMembers>
     */
    public function getProjectMembers(): Collection
    {
        return $this->projectMembers;
    }

    public function addProjectMember(ProjectMembers $projectMember): static
    {
        if (!$this->projectMembers->contains($projectMember)) {
            $this->projectMembers->add($projectMember);
            $projectMember->addStudent($this);
        }

        return $this;
    }

    public function removeProjectMember(ProjectMembers $projectMember): static
    {
        if ($this->projectMembers->removeElement($projectMember)) {
            $projectMember->removeStudent($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setSender($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getSender() === $this) {
                $message->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setUploadedBy($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getUploadedBy() === $this) {
                $file->setUploadedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Events>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Events $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setAdmin($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getAdmin() === $this) {
                $event->setAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EventReactions>
     */
    public function getEventReactions(): Collection
    {
        return $this->eventReactions;
    }

    public function addEventReaction(EventReactions $eventReaction): static
    {
        if (!$this->eventReactions->contains($eventReaction)) {
            $this->eventReactions->add($eventReaction);
            $eventReaction->setUser($this);
        }

        return $this;
    }

    public function removeEventReaction(EventReactions $eventReaction): static
    {
        if ($this->eventReactions->removeElement($eventReaction)) {
            // set the owning side to null (unless already changed)
            if ($eventReaction->getUser() === $this) {
                $eventReaction->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EventComments>
     */
    public function getEventComments(): Collection
    {
        return $this->eventComments;
    }

    public function addEventComment(EventComments $eventComment): static
    {
        if (!$this->eventComments->contains($eventComment)) {
            $this->eventComments->add($eventComment);
            $eventComment->setUser($this);
        }

        return $this;
    }

    public function removeEventComment(EventComments $eventComment): static
    {
        if ($this->eventComments->removeElement($eventComment)) {
            // set the owning side to null (unless already changed)
            if ($eventComment->getUser() === $this) {
                $eventComment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notifications>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notifications $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notifications $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Events>
     */
    public function getEventParticipant(): Collection
    {
        return $this->event_participant;
    }

    public function addEventParticipant(Events $eventParticipant): static
    {
        if (!$this->event_participant->contains($eventParticipant)) {
            $this->event_participant->add($eventParticipant);
            $eventParticipant->addParticipant($this);
        }

        return $this;
    }

    public function removeEventParticipant(Events $eventParticipant): static
    {
        if ($this->event_participant->removeElement($eventParticipant)) {
            $eventParticipant->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ResetPasswordToken>
     */
    public function getResetPasswordTokens(): Collection
    {
        return $this->resetPasswordTokens;
    }

    public function addResetPasswordToken(ResetPasswordToken $resetPasswordToken): static
    {
        if (!$this->resetPasswordTokens->contains($resetPasswordToken)) {
            $this->resetPasswordTokens->add($resetPasswordToken);
            $resetPasswordToken->setUser($this);
        }

        return $this;
    }

    public function removeResetPasswordToken(ResetPasswordToken $resetPasswordToken): static
    {
        if ($this->resetPasswordTokens->removeElement($resetPasswordToken)) {
            // set the owning side to null (unless already changed)
            if ($resetPasswordToken->getUser() === $this) {
                $resetPasswordToken->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FollowNotification>
     */
    public function getFollowNotifications(): Collection
    {
        return $this->followNotifications;
    }

    public function addFollowNotification(FollowNotification $followNotification): static
    {
        if (!$this->followNotifications->contains($followNotification)) {
            $this->followNotifications->add($followNotification);
            $followNotification->setUser($this);
        }

        return $this;
    }

    public function removeFollowNotification(FollowNotification $followNotification): static
    {
        if ($this->followNotifications->removeElement($followNotification)) {
            // set the owning side to null (unless already changed)
            if ($followNotification->getUser() === $this) {
                $followNotification->setUser(null);
            }
        }

        return $this;
    }


}
