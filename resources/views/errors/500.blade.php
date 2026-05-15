@include('errors.layout', [
    'codigo'   => '500',
    'icone'    => '⚙️',
    'titulo'   => 'Erro interno do servidor',
    'mensagem' => 'Algo deu errado no servidor. Tente novamente em alguns instantes. Se o problema persistir, entre em contato com o administrador.',
])