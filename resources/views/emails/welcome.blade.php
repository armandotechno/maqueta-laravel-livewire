<!DOCTYPE html>
<html>

<head>
    <title>Bienvenido al Sistema</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">

    <div
        style="max-w-md: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">

        <h2 style="color: #03295a; text-align: center;">¡Hola, {{ $user->name }}!</h2>

        <p style="color: #333333; font-size: 16px;">Has sido registrado exitosamente en nuestro sistema.</p>

        <p style="color: #333333; font-size: 16px;">A continuación, te proporcionamos tus credenciales de acceso
            temporal. Por motivos de seguridad, el sistema te pedirá que cambies esta contraseña la primera vez que
            ingreses.</p>

        <div
            style="background-color: #f0f7ff; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #03295a;">
            <p style="margin: 0; font-size: 16px;"><strong>URL de acceso:</strong> <a
                    href="{{ route('home') }}">{{ route('home') }}</a></p>
            <p style="margin: 10px 0 0 0; font-size: 16px;"><strong>Usuario (Email):</strong> {{ $user->email }}</p>
            <p style="margin: 10px 0 0 0; font-size: 16px;"><strong>Contraseña Temporal:</strong> {{ $password }}</p>
        </div>

        <p style="color: #777777; font-size: 12px; text-align: center; margin-top: 30px;">
            Este es un correo automático, por favor no respondas a esta dirección.
        </p>
    </div>

</body>

</html>
