<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Linhas de Linguagem para Redefinição de Senha
    |--------------------------------------------------------------------------
    |
    | As seguintes linhas de linguagem são as linhas padrão que correspondem aos motivos
    | que são dados pelo corretor de senhas para uma tentativa de atualização de senha
    | que falhou, como um token inválido ou uma nova senha inválida.
    |
    */

    'reset' => 'Sua senha foi redefinida.',
    'sent' => 'Enviamos seu link de redefinição de senha por e-mail.',
    'throttled' => 'Por favor, aguarde antes de tentar novamente.',
    'token' => 'Este token de redefinição de senha é inválido.',
    'user' => 'Não conseguimos encontrar um usuário com esse endereço de e-mail.',
    
    // Laravel default messages
    'Whoops!' => 'Ops!',
    'Hello!' => 'Olá!',
    'Regards,' => 'Atenciosamente,',
    
    // Email notifications
    'reset_password_notification' => [
        'subject' => 'Notificação de Redefinição de Senha',
        'greeting' => 'Olá!',
        'line_1' => 'Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.',
        'action' => 'Redefinir Senha',
        'line_2' => 'Este link de redefinição de senha expirará em :count minutos.',
        'line_3' => 'Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.',
        'salutation' => 'Atenciosamente,<br>:app_name',
        'trouble_clicking' => "Se você está tendo problemas para clicar no botão \":actionText\", copie e cole a URL abaixo\nem seu navegador:",
    ],
]; 