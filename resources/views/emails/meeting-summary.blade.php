@component('mail::message')
# 🧠 Resumen de tu reunión: {{ $meeting->titulo }}

**Fecha:** {{ $meeting->created_at->format('d/m/Y H:i') }}

---

## 📋 Resumen
{!! $meeting->resumen !!}

---

@if($meeting->insight)
## 💡 Insight conductual
{!! Str::markdown($meeting->insight) !!}
@endif

---

@if($meeting->tasks->count() > 0)
## ✅ Tareas extraídas
@foreach($meeting->tasks as $task)
- {{ $task->descripcion }}
@endforeach
@endif

---

Gracias por usar **Sumora** ⚡  
@endcomponent
