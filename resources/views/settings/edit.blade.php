@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('settings.update') }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Offer Apply Mode</label>
            <select name="offer_apply_mode" class="form-select">
                <option value="quantity" @selected($settings['offer_apply_mode'] === 'quantity')>By Quantity (pieces sold)</option>
                <option value="piece" @selected($settings['offer_apply_mode'] === 'piece')>By Piece (per unit)</option>
            </select>
            <div class="form-text">Controls how Free Offer "buy X get Y free" triggers are calculated.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Purchase Order Invoice Prefix</label>
            <input type="text" name="invoice_prefix" class="form-control" value="{{ $settings['invoice_prefix'] }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Default Low Stock Threshold (ctn)</label>
            <input type="number" name="low_stock_default_threshold" class="form-control" value="{{ $settings['low_stock_default_threshold'] }}">
        </div>
        <button class="btn btn-primary" type="submit">Save Settings</button>
    </form>
</div>
@endsection
