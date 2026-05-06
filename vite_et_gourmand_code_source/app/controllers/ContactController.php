<?php
// app/controllers/ContactController.php
class ContactController
{
    public function form(): void
    {
        view('contact/form', ['csrf' => Session::generateCsrf()]);
    }

    public function send(): void
    {
        if (!Session::verifyCsrf(post('csrf_token'))) redirect('/contact');

        $titre       = post('titre');
        $description = post('description');
        $email       = post('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($titre)) {
            Session::flash('error', 'Veuillez remplir tous les champs correctement.');
            redirect('/contact');
        }

        $html = "<h3>Nouveau message de contact</h3>
                 <p><strong>Titre :</strong> " . htmlspecialchars($titre) . "</p>
                 <p><strong>Email :</strong> " . htmlspecialchars($email) . "</p>
                 <p><strong>Message :</strong><br>" . nl2br(htmlspecialchars($description)) . "</p>";

        sendMail($_ENV['CONTACT_MAIL'] ?? 'contact@viteetgourmand.fr', '[Contact] ' . $titre, $html);

        Session::flash('success', 'Votre message a été envoyé. Nous vous répondrons sous 48h.');
        redirect('/contact');
    }
}

// app/controllers/PageController.php
class PageController
{
    public function mentions(): void
    {
        $pageTitle = 'Mentions légales';
        view('pages/mentions', ['pageTitle' => $pageTitle]);
    }

    public function cgv(): void
    {
        $pageTitle = 'Conditions Générales de Vente';
        view('pages/cgv', ['pageTitle' => $pageTitle]);
    }
}
