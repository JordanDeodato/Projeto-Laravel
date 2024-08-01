@extends('layouts.main')

@section('title', 'Criar Eventos')

@section('content')

    <div id="event-create-container" class="col-md-6 offset-md-3">
        <h1>Crie o seu evento</h1>
        <form action="/events" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="image">Imagem do Evento:</label>
                <input type="file" class="form-control-image" id="image" name="image">
                @if ($errors->has('image'))
                    <div>
                        <span class="text-danger">{{ $errors->first('image') }}</span>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="title">Evento:</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Nome do evento" value="{{ old('title') }}">
                @if ($errors->has('title'))
                    <div>
                        <span class="text-danger">{{ $errors->first('title') }}</span>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="date">Data do Evento:</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}">
                @if ($errors->has('date'))
                    <div>
                        <span class="text-danger">{{ $errors->first('date') }}</span>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="city">Cidade:</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Local do evento" value="{{ old('city') }}">
                @if ($errors->has('city'))
                    <div>
                        <span class="text-danger">{{ $errors->first('city') }}</span>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="private">O evento é privado?</label>
                <select name="private" id="private" class="form-control">
                    <option value="0" {{ old('private') == '0' ? 'selected' : '' }}>Não</option>
                    <option value="1" {{ old('private') == '1' ? 'selected' : '' }}>Sim</option>
                </select>
                @if ($errors->has('private'))
                    <div>
                        <span class="text-danger">{{ $errors->first('private') }}</span>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="description">Descrição:</label>
                <textarea name="description" id="description" class="form-control" placeholder="O que vai acontecer no evento?">{{ old('description') }}</textarea>
                @if ($errors->has('description'))
                    <div>
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="items">Adicione itens de infraestrutura:</label>
                @php
                    $items = old('items', []);
                @endphp
                <div class="form-check">
                    <input type="checkbox" name="items[]" id="items1" value="Cadeiras" class="form-check-input" {{ in_array('Cadeiras', $items) ? 'checked' : '' }}>
                    <label class="form-check-label" for="items1">Cadeiras</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="items[]" id="items2" value="Palco" class="form-check-input" {{ in_array('Palco', $items) ? 'checked' : '' }}>
                    <label class="form-check-label" for="items2">Palco</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="items[]" id="items3" value="Cerveja Grátis" class="form-check-input" {{ in_array('Cerveja Grátis', $items) ? 'checked' : '' }}>
                    <label class="form-check-label" for="items3">Cerveja Grátis</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="items[]" id="items4" value="Open Food" class="form-check-input" {{ in_array('Open Food', $items) ? 'checked' : '' }}>
                    <label class="form-check-label" for="items4">Open Food</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="items[]" id="items5" value="Brindes" class="form-check-input" {{ in_array('Brindes', $items) ? 'checked' : '' }}>
                    <label class="form-check-label" for="items5">Brindes</label>
                </div>
                @if ($errors->has('items'))
                    <div>
                        <span class="text-danger">{{ $errors->first('items') }}</span>
                    </div>
                @endif
            </div>
            <input type="submit" class="btn btn-primary" value="Criar Evento">
        </form>
    </div>

@endsection
