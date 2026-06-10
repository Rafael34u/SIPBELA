const fs = require('fs');
const path = require('path');

function walkDir(dir, callback) {
    fs.readdirSync(dir).forEach(f => {
        let dirPath = path.join(dir, f);
        let isDirectory = fs.statSync(dirPath).isDirectory();
        isDirectory ? walkDir(dirPath, callback) : callback(path.join(dir, f));
    });
}

const viewsDir = path.join(__dirname, 'resources', 'views');

walkDir(viewsDir, (filePath) => {
    if (filePath.endsWith('.blade.php')) {
        let content = fs.readFileSync(filePath, 'utf8');
        // Regex to match @section('sidebar-links') ... @endsection
        // The /s flag allows . to match newlines
        const regex = /@section\('sidebar-links'\)[\s\S]*?@endsection\s*/g;
        if (regex.test(content)) {
            const newContent = content.replace(regex, '');
            fs.writeFileSync(filePath, newContent, 'utf8');
            console.log(`Cleaned ${filePath}`);
        }
    }
});
