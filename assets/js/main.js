// ZooTrack — Main JS

// Silme onayı
document.addEventListener('DOMContentLoaded', () => {

    // Silme butonları için onay penceresi
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const name = btn.dataset.name || 'bu kaydı';
            if (!confirm(`"${name}" adlı hayvanı silmek istediğinizden emin misiniz?\nBu işlem geri alınamaz.`)) {
                e.preventDefault();
            }
        });
    });

    // Flash mesajlarını 4 saniye sonra gizle
    const alerts = document.querySelectorAll('.auto-dismiss');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    // Hayvan türüne göre emoji seçici
    const speciesInput = document.getElementById('species');
    const emojiPreview = document.getElementById('emoji-preview');

    const speciesEmoji = {
        'aslan': '🦁', 'lion': '🦁',
        'kaplan': '🐯', 'tiger': '🐯',
        'fil': '🐘', 'elephant': '🐘',
        'zürafa': '🦒', 'giraffe': '🦒',
        'penguen': '🐧', 'penguin': '🐧',
        'maymun': '🐒', 'monkey': '🐒',
        'zebra': '🦓',
        'panda': '🐼',
        'ayı': '🐻', 'bear': '🐻',
        'kurt': '🐺', 'wolf': '🐺',
        'timsah': '🐊', 'crocodile': '🐊',
        'yılan': '🐍', 'snake': '🐍',
        'kartal': '🦅', 'eagle': '🦅',
        'papağan': '🦜', 'parrot': '🦜',
        'flamingo': '🦩',
        'deve': '🐪', 'camel': '🐪',
        'hipopotam': '🦛', 'hippo': '🦛',
        'gergedan': '🦏', 'rhino': '🦏',
        'leopar': '🐆', 'leopard': '🐆',
        'çita': '🐆', 'cheetah': '🐆',
        'köpek balığı': '🦈', 'shark': '🦈',
        'balina': '🐋', 'whale': '🐋',
        'yunus': '🐬', 'dolphin': '🐬',
        'kaplumbağa': '🐢', 'turtle': '🐢',
        'kanguru': '🦘', 'kangaroo': '🦘',
        'koala': '🐨',
        'jaguar': '🐆',
        'orangutan': '🦧',
        'goril': '🦍', 'gorilla': '🦍',
        'tur': '🐂', 'bison': '🦬',
        'deve kuşu': '🦤', 'ostrich': '🦤',
    };

    if (speciesInput && emojiPreview) {
        speciesInput.addEventListener('input', () => {
            const val = speciesInput.value.toLowerCase().trim();
            let found = '🐾';
            for (const [key, emoji] of Object.entries(speciesEmoji)) {
                if (val.includes(key)) { found = emoji; break; }
            }
            emojiPreview.textContent = found;
        });
    }
});

// Hayvan kartları için emoji eşleme (liste sayfasında kullanılır)
function getAnimalEmoji(species) {
    const map = {
        'aslan':'🦁','lion':'🦁','kaplan':'🐯','tiger':'🐯',
        'fil':'🐘','elephant':'🐘','zürafa':'🦒','giraffe':'🦒',
        'penguen':'🐧','maymun':'🐒','zebra':'🦓','panda':'🐼',
        'ayı':'🐻','bear':'🐻','kurt':'🐺','timsah':'🐊',
        'yılan':'🐍','kartal':'🦅','papağan':'🦜','flamingo':'🦩',
        'deve':'🐪','hipopotam':'🦛','gergedan':'🦏','leopar':'🐆',
        'çita':'🐆','kanguru':'🦘','koala':'🐨','goril':'🦍',
        'orangutan':'🦧','balina':'🐋','yunus':'🐬','kaplumbağa':'🐢',
    };
    if (!species) return '🐾';
    const s = species.toLowerCase();
    for (const [key, emoji] of Object.entries(map)) {
        if (s.includes(key)) return emoji;
    }
    return '🐾';
}
