<!DOCTYPE html>
<html>
<head>
    <title>Estado de tu reserva</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Hola, {{ $reservation->user_name }}</h2>
    
    <p>{{ $actionMessage }}</p>

    <div style="background-color: #f4f4f4; padding: 15px; border-radius: 5px;">
        <p><strong>Cancha:</strong> {{ $reservation->space->name }}</p>
        <p><strong>Fecha y Hora de inicio:</strong> {{ $reservation->start_time }}</p>
        <p><strong>Fecha y Hora de fin:</strong> {{ $reservation->end_time }}</p>
        <p><strong>Estado Actual:</strong> <span style="text-transform: uppercase; font-weight: bold;">{{ $reservation->status }}</span></p>
    </div>

    <p>Gracias por usar nuestro sistema de reservas.</p>
</body>
</html>