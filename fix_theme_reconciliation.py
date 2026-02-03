
import os

file_path = r'c:\Users\STAN\EduPass-MG\resources\views\admin\reconciliation\index.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix Headings and Text
content = content.replace('text-3xl font-bold text-gray-800', 'text-3xl font-bold text-gray-800 dark:text-white')
content = content.replace('text-gray-600', 'text-gray-600 dark:text-gray-400')
content = content.replace('text-xl font-semibold mb-4', 'text-xl font-semibold mb-4 dark:text-white')
content = content.replace('text-xl font-semibold', 'text-xl font-semibold dark:text-white')

# Fix Panels
content = content.replace('bg-white rounded-lg shadow p-6 mb-6', 'bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6')
content = content.replace('bg-white rounded-lg shadow overflow-hidden', 'bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden')
content = content.replace('bg-gray-50 border-b', 'bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600')
content = content.replace('bg-gray-50', 'bg-gray-50 dark:bg-gray-700')

# Fix Table
content = content.replace('bg-gray-100', 'bg-gray-100 dark:bg-gray-700')
content = content.replace('text-gray-700', 'text-gray-700 dark:text-gray-300')
content = content.replace('divide-gray-200', 'divide-gray-200 dark:divide-gray-600')
content = content.replace('hover:bg-gray-50', 'hover:bg-gray-50 dark:hover:bg-gray-600')
content = content.replace('text-sm font-medium text-gray-700 mb-2', 'text-sm font-medium text-gray-700 dark:text-gray-300 mb-2')
content = content.replace('border border-gray-300 rounded-lg px-4 py-2', 'border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2')

# Fix Stats Cards (they have bg-xxx-100, maybe keep them or adjust)
# Let's make them more dark-mode friendly
content = content.replace('bg-blue-100 p-6 rounded-lg shadow', 'bg-blue-100 dark:bg-blue-900/30 p-6 rounded-lg shadow')
content = content.replace('text-blue-800', 'text-blue-800 dark:text-blue-300')
content = content.replace('text-blue-900', 'text-blue-900 dark:text-blue-200')

content = content.replace('bg-yellow-100 p-6 rounded-lg shadow', 'bg-yellow-100 dark:bg-yellow-900/30 p-6 rounded-lg shadow')
content = content.replace('text-yellow-800', 'text-yellow-800 dark:text-yellow-300')
content = content.replace('text-yellow-900', 'text-yellow-900 dark:text-yellow-200')

content = content.replace('bg-green-100 p-6 rounded-lg shadow', 'bg-green-100 dark:bg-green-900/30 p-6 rounded-lg shadow')
content = content.replace('text-green-800', 'text-green-800 dark:text-green-300')
content = content.replace('text-green-900', 'text-green-900 dark:text-green-200')

content = content.replace('bg-purple-100 p-6 rounded-lg shadow', 'bg-purple-100 dark:bg-purple-900/30 p-6 rounded-lg shadow')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Theme fixes applied to reconciliation index.")
