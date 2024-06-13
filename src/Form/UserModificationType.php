<?php
 
 
namespace App\Form;
 
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserModificationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add('profilePicture', FileType::class, [
                'label'=> 'Profile Picture',
                // unmapped means that this filed is not associated to any entity property
                'mapped' => false,
                // optional so I don't have to re-upload the picture every time the user detail is edited
                'required' => false,
                // unmapped fields can't define their validation using attributes in se associated entity
                // so I use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k', //1MB
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png', 
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid profile picture (jpeg / png / webp)',
                    ])
                ]
            ])
            ->add('valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // 'validation_groups' => ['registration'],
        ]);
    }



}