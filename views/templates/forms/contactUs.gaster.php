@layout('main')
@section('title')Contact Us@endsection
@section('content')

<?php
use Model\ContactUs;
use Simfa\Form\Form;
use Simfa\Framework\Application;
?>
    <h1>Contact Us</h1>

    <?php
    /** @var ContactUs $contact */
    $form = Form::begin($contact,Application::path('contact.us'));
    echo $form->field('title')->required();
    if (Application::isGuest())
        echo $form->field('email')->required();
    else
        echo $form->field('email')->disabled()->default(Application::$APP->user->getEmail());
    echo $form->text('content')->setLabel('Message')->required();
    echo $form->submit('Send');
    $form::end();

    ?>
@endsection
