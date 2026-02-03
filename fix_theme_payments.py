
import os

file_path = r'c:\Users\STAN\EduPass-MG\resources\views\admin\payments\index.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix Table Header
content = content.replace('text-gray-400 text-xs uppercase tracking-widest font-bold', 'text-gray-500 dark:text-gray-400 text-xs uppercase tracking-widest font-bold')

# Fix Email, Transaction ID, Date, and Empty State
content = content.replace('text-xs text-gray-500">{{ $payment->user->email }}', 'text-xs text-gray-500 dark:text-gray-400">{{ $payment->user->email }}')
content = content.replace('text-xs font-mono text-gray-500">{{ $payment->transaction_id }}', 'text-xs font-mono text-gray-500 dark:text-gray-400">{{ $payment->transaction_id }}')
content = content.replace('text-xs text-gray-500">\n                                            {{ $payment->created_at->format(\'d/m/Y H:i\') }}', 'text-xs text-gray-500 dark:text-gray-400">\n                                            {{ $payment->created_at->format(\'d/m/Y H:i\') }}')
content = content.replace('colspan="6" class="px-6 py-8 text-center text-gray-500">Aucun paiement trouvé.', 'colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Aucun paiement trouvé.')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Theme fixes applied to payments index.")
