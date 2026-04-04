import re

with open('app/views/transpoter/setting.view.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Remove specific lines
content = re.sub(r'^\s*confirmDeleteBtn\.addEventListener.*?$\n?', '', content, flags=re.MULTILINE)
content = re.sub(r'^\s*reasonRadios.*?toggleFeedbackField\)\);\n?', '', content, flags=re.MULTILINE)
content = re.sub(r'^\s*window\.addEventListener.*?hideDeleteModal\(\);\s*\}\s*\}\);\n?', '', content, flags=re.MULTILINE | re.DOTALL)
content = re.sub(r'^\s*// Close modal when clicking outside\n?', '', content, flags=re.MULTILINE)

# Functions to remove by regex
content = re.sub(r'^\s*function hideDeleteModal\(\) \{.*?\n\s*\}\n?', '', content, flags=re.MULTILINE | re.DOTALL)
content = re.sub(r'^\s*async function handleAccountDeletion\(\) \{.*?\n\s*\}\n?', '', content, flags=re.MULTILINE | re.DOTALL)
content = re.sub(r'^\s*function toggleFeedbackField\(\) \{.*?\n\s*\}\n?', '', content, flags=re.MULTILINE | re.DOTALL)
content = re.sub(r'^\s*function validateDeleteForm\(\) \{.*?\n\s*\}\n?', '', content, flags=re.MULTILINE | re.DOTALL)

with open('app/views/transpoter/setting.view.php', 'w', encoding='utf-8') as f:
    f.write(content)
