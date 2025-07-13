import React from 'react';

export default function LoginEnhancer() {
    const [email, setEmail] = React.useState('');
    const [password, setPassword] = React.useState('');

    return (
        <div style={{ marginBottom: '10px', fontSize: '14px' }}>
            <p>🔒 Mejora de React cargada</p>
            <p>Correo digitado: <strong>{email}</strong></p>
            <p>Contraseña (oculta): {password.length > 0 ? '●●●●●' : 'No escrita'}</p>
        </div>
    );
}
