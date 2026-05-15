@include('errors.layout', [
    'codigo'   => '403',
    'icone'    => '🚫',
    'titulo'   => 'Acesso Negado',
    'mensagem' => 'Você não tem permissão para acessar esta página. Entre em contato com o administrador do sistema caso precise de acesso.',
])