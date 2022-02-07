@layout('main')
@section('title')Contact Us@endsection
@section('content')
    <?php
    use core\Application;
    use core\Form\Form;
    use models\ContactUs;
    ?>
    <h1>Contact Us</h1>

    <?php
    /** @var ContactUs $contact */
    $form = Form::begin(Application::path('contact.us'));
    echo $form->field($contact, 'title')->required()->setClass('testtttt');
    if (Application::isGuest())
        echo $form->field($contact, 'email')->required()->setClass('azure');
    else
        echo $form->field($contact, 'email')->disabled()->default(Application::$APP->user->email)->setClass('azure');
        ?>
    
    <?php echo $form->text($contact, 'content')->setLabel('Message')->required()->setClass('mdd'); ?>
    <?php echo $form->submit('Send'); 
    $form::end();
    ?>
    
@endsection
