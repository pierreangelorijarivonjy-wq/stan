
import os

file_path = r'c:\Users\STAN\EduPass-MG\resources\views\admin\students\index.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix Matricule and Email
content = content.replace('text-sm font-mono text-gray-500">{{ $student->matricule }}', 'text-sm font-mono text-gray-500 dark:text-gray-400">{{ $student->matricule }}')
content = content.replace('text-sm text-gray-500">{{ $student->user->email }}', 'text-sm text-gray-500 dark:text-gray-400">{{ $student->user->email }}')

# Fix Date
content = content.replace('text-xs text-gray-500">\n                                            {{ $student->created_at->format(\'d/m/Y\') }}', 'text-xs text-gray-500 dark:text-gray-400">\n                                            {{ $student->created_at->format(\'d/m/Y\') }}')

# Fix Course Badges
content = content.replace('bg-indigo-50 text-indigo-600 border border-indigo-100', 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800')

# Fix Payment Date and Empty State
content = content.replace('text-[10px] text-gray-400">{{ $latestPayment->created_at->format(\'d/m/Y\') }}', 'text-[10px] text-gray-500 dark:text-gray-400">{{ $latestPayment->created_at->format(\'d/m/Y\') }}')
content = content.replace('text-xs text-gray-400">Aucun paiement', 'text-xs text-gray-500 dark:text-gray-400">Aucun paiement')
content = content.replace('colspan="8" class="px-6 py-8 text-center text-gray-500">Aucun étudiant trouvé.', 'colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Aucun étudiant trouvé.')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Theme fixes applied to students index.")
