<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Central de Suporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #121212;
        color: #ffffff !important;
    }
    .card {
        background-color: #1e1e1e;
        border: 1px solid #2c2c2c;
        color: #ffffff !important;
    }
    h1, h3, h4, h5, h6, p, label, .card-title, .card-subtitle {
        color: #ffffff !important;
    }
    .form-control,
.form-select {
    background-color: #2c2c2c;
    color: #ffffff !important; /* por padrão, texto branco */
    border: 1px solid #444;
}

/* Quando estiver digitando ou com foco: texto preto */
.form-control:focus,
.form-select:focus {
    color: #000000 !important;
}

/* Placeholder continua mais escuro */
.form-control::placeholder {
    color: #666 !important;
}
    .form-select option {
        background-color: #2c2c2c;
        color: #ffffff;
    }
    .btn-primary {
        background-color: #4a90e2;
        border: none;
    }
    .btn-outline-primary {
        border-color: #4a90e2;
        color: #4a90e2;
    }
    .btn-outline-primary:hover {
        background-color: #4a90e2;
        color: #fff;
    }
    .badge-aberto {
        background-color: #ffc107;
        color: #000;
    }
    .badge-em-andamento {
        background-color: #17a2b8;
    }
    .badge-resolvido {
        background-color: #28a745;
    }
    .alert {
        background-color: #2c2c2c;
        color: #ffffff !important;
        border: 1px solid #444;
    }
    .text-muted {
        color: #cccccc !important;
    }
</style>

</head>
<body class="container py-5">

    <h1 class="mb-4 text-light">📩 Central de Suporte</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('tickets.store') }}" method="POST" class="card p-4 mb-5">
        @csrf
        <h4 class="mb-3 text-light">Novo Ticket</h4>
        <div class="mb-3">
            <label class="text-light">Nome:</label>
            <input type="text" name="nome" class="form-control" placeholder="Seu nome" required>
        </div>
        <div class="mb-3">
            <label class="text-light">E-mail:</label>
            <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
        </div>
        <div class="mb-3">
            <label class="text-light">Problema:</label>
            <input type="text" name="problema" class="form-control" placeholder="Resumo do problema" required>
        </div>
        <div class="mb-3">
            <label class="text-light">Descrição:</label>
            <textarea name="descricao" rows="4" class="form-control" placeholder="Descreva o problema..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Ticket</button>
    </form>

    @foreach(['Aberto', 'Em andamento', 'Resolvido'] as $status)
        <h3 class="mt-5 text-light">
            {{ $status }}
            @php
                $badgeClass = match($status) {
                    'Aberto' => 'badge-aberto',
                    'Em andamento' => 'badge-em-andamento',
                    'Resolvido' => 'badge-resolvido',
                };
            @endphp
            <span class="badge {{ $badgeClass }}">{{ $status }}</span>
        </h3>

        @forelse($tickets[$status] ?? [] as $ticket)
            <div class="card mb-3 shadow-sm text-light">
                <div class="card-body">
                    <h5 class="card-title text-light">{{ $ticket->problema }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $ticket->nome }} ({{ $ticket->email }})</h6>
                    <p class="card-text">{{ $ticket->descricao }}</p>

                    <!-- Formulário de alteração de status -->
                    <form action="{{ route('tickets.updateStatus', $ticket) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')

                        <div class="input-group">
                            <select name="status" class="form-select">
                                <option value="Aberto" {{ $ticket->status == 'Aberto' ? 'selected' : '' }}>Aberto</option>
                                <option value="Em andamento" {{ $ticket->status == 'Em andamento' ? 'selected' : '' }}>Em andamento</option>
                                <option value="Resolvido" {{ $ticket->status == 'Resolvido' ? 'selected' : '' }}>Resolvido</option>
                            </select>
                            <button type="submit" class="btn btn-outline-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-muted">Nenhum ticket "{{ $status }}"</p>
        @endforelse
    @endforeach

</body>
</html>
