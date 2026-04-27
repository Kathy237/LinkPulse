<x-app-layout>
    <!-- Vue d'inscription : Collecte email, nom, téléphone et localisation -->
    <!-- Design moderne et épuré "Google Antigravity" -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-background">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-surface shadow-float sm:rounded-lg transition-transform duration-300 hover:scale-[1.01]">
            <h2 class="text-2xl font-sans font-semibold text-secondary text-center mb-6">Créer un compte</h2>

            <form method="POST" action="/register" id="registerForm" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block font-medium text-sm text-secondary">Email</label>
                    <input id="email" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 rounded-md shadow-sm" type="email" name="email" required autofocus autocomplete="username" />
                </div>

                <!-- Nom -->
                <div>
                    <label for="name" class="block font-medium text-sm text-secondary">Nom</label>
                    <input id="name" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 rounded-md shadow-sm" type="text" name="name" required />
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="phone" class="block font-medium text-sm text-secondary">Téléphone</label>
                    <input id="phone" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 rounded-md shadow-sm" type="text" name="phone" required />
                </div>

                <!-- Localisation -->
                <div>
                    <label for="location" class="block font-medium text-sm text-secondary">Localisation</label>
                    <input id="location" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 rounded-md shadow-sm" type="text" name="location" required />
                </div>

                <!-- Mot de passe -->
                <div>
                    <label for="password" class="block font-medium text-sm text-secondary">Mot de passe</label>
                    <input id="password" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 rounded-md shadow-sm" type="password" name="password" required autocomplete="new-password" />
                </div>

                <!-- Confirmation Mot de passe -->
                <div>
                    <label for="password_confirmation" class="block font-medium text-sm text-secondary">Confirmer le mot de passe</label>
                    <input id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 rounded-md shadow-sm" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150">
                        S'inscrire
                    </button>
                </div>
            </form>

            <!-- Zone d'affichage du message après validation -->
            <div id="statusMessage" class="hidden mt-4 p-4 text-sm rounded-md text-center"></div>
        </div>
    </div>

    <!-- Script pour soumettre le formulaire avec Fetch API (pas besoin d'axios en inline) -->
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            const statusMessage = document.getElementById('statusMessage');

            // Appel API vers le backend Laravel (URL absolue vers l'API découplée)
            fetch('http://localhost:8000/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(async response => {
                const resData = await response.json();
                if (!response.ok) {
                    throw new Error(resData.message || 'Une erreur est survenue.');
                }
                document.getElementById('registerForm').reset();
                statusMessage.classList.remove('hidden', 'bg-red-100', 'text-error');
                statusMessage.classList.add('bg-green-100', 'text-green-800');
                statusMessage.textContent = resData.message || 'Votre compte est en attente de validation par l\'administrateur.';
            })
            .catch(error => {
                statusMessage.classList.remove('hidden', 'bg-green-100', 'text-green-800');
                statusMessage.classList.add('bg-red-100', 'text-error');
                statusMessage.textContent = error.message || 'Une erreur est survenue lors de l\'inscription.';
            });
        });
    </script>
</x-app-layout>
