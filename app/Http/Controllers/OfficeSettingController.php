<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOfficeSettingRequest;
use App\Models\OfficeSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OfficeSettingController extends Controller
{
    public function edit(): View
    {
        $officeSetting = OfficeSetting::firstOrCreate([], [
            'office_name' => 'OFICINA JURIDICA ALVARO CALDERON S.',
            'office_address' => 'Tiquisate, Escuintla',
            'office_phone' => '78855919',
            'notary_name' => 'Álvaro Calderón Sagastume',
        ]);

        return view('office-settings.edit', compact('officeSetting'));
    }

    public function update(UpdateOfficeSettingRequest $request): RedirectResponse
    {
        $officeSetting = OfficeSetting::firstOrCreate([], [
            'office_name' => 'OFICINA JURIDICA ALVARO CALDERON S.',
            'office_address' => 'Tiquisate, Escuintla',
            'office_phone' => '78855919',
            'notary_name' => 'Álvaro Calderón Sagastume',
        ]);

        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('office', 'public');
        }

        if ($request->hasFile('signature')) {
            $data['signature_path'] = $request->file('signature')->store('office', 'public');
        }

        $data['show_signature'] = $request->boolean('show_signature');
        $data['allow_overpayment'] = $request->boolean('allow_overpayment');
        $data['use_digital_signature_provider'] = $request->boolean('use_digital_signature_provider');

        $officeSetting->update($data);

        return redirect()
            ->route('office-settings.edit')
            ->with('status', 'Configuracion guardada correctamente.');
    }
}
