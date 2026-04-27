<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-secondary leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-background min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Statistiques (Cards Antigravity) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6" id="statsContainer">
                <div class="bg-surface overflow-hidden shadow-float rounded-lg p-6 transition-transform duration-300 hover:scale-105">
                    <div class="text-sm font-medium text-secondary truncate">Vues totales ce mois</div>
                    <div class="mt-1 text-3xl font-semibold text-primary" id="totalViews">--</div>
                </div>
                <div class="bg-surface overflow-hidden shadow-float rounded-lg p-6 transition-transform duration-300 hover:scale-105">
                    <div class="text-sm font-medium text-secondary truncate">Vues via QR Code</div>
                    <div class="mt-1 text-3xl font-semibold text-primary" id="qrViews">--</div>
                </div>
                <div class="bg-surface overflow-hidden shadow-float rounded-lg p-6 transition-transform duration-300 hover:scale-105">
                    <div class="text-sm font-medium text-secondary truncate">Vues via Lien Direct</div>
                    <div class="mt-1 text-3xl font-semibold text-primary" id="linkViews">--</div>
                </div>
            </div>

            <!-- Liste des portfolios sans carte NFC -->
            <div class="bg-surface overflow-hidden shadow-float rounded-lg">
                <div class="p-6 text-secondary">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Portfolios en attente de liaison NFC</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créé le</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Action</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="pendingPortfoliosBody">
                                <tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Chargement...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Liste des notifications -->
            <div class="bg-surface overflow-hidden shadow-float rounded-lg">
                <div class="p-6 text-secondary">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Dernières notifications</h3>
                    <ul class="divide-y divide-gray-200" id="notificationsList">
                        <li class="py-4 text-sm text-gray-500 text-center">Chargement...</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <!-- Script pour la récupération des données (utilisation de l'objet axios global via Vite ou Fetch API) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const apiBaseUrl = 'http://localhost:8000'; // À adapter selon l'environnement (ex: process.env.VITE_API_URL)

            // 1. Charger les statistiques
            fetch(`${apiBaseUrl}/api/dashboard/statistics`, {
                headers: { 'Accept': 'application/json' }
            })
                .then(res => res.json())
                .then(data => {
                    const responseData = data.data || data; // Gérer les formats de réponse
                    document.getElementById('totalViews').innerText = responseData.total_views || 0;
                    document.getElementById('qrViews').innerText = responseData.qr_views || 0;
                    document.getElementById('linkViews').innerText = responseData.link_views || 0;
                })
                .catch(error => console.error('Erreur stats:', error));

            // 2. Charger les portfolios sans NFC
            fetch(`${apiBaseUrl}/api/portfolios/pending-nfc`, {
                headers: { 'Accept': 'application/json' }
            })
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('pendingPortfoliosBody');
                    tbody.innerHTML = '';
                    const portfolios = data.data || data;
                    if (!portfolios || portfolios.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Aucun portfolio en attente.</td></tr>';
                    } else {
                        portfolios.forEach(p => {
                            tbody.innerHTML += `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${p.display_name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(p.created_at).toLocaleDateString()}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="#" class="text-primary hover:text-blue-700">Associer une carte</a>
                                    </td>
                                </tr>`;
                        });
                    }
                })
                .catch(error => console.error('Erreur portfolios:', error));

            // 3. Charger les notifications
            fetch(`${apiBaseUrl}/api/dashboard/notifications`, {
                headers: { 'Accept': 'application/json' }
            })
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById('notificationsList');
                    list.innerHTML = '';
                    const notifs = data.data || data;
                    if (!notifs || notifs.length === 0) {
                        list.innerHTML = '<li class="py-4 text-sm text-gray-500 text-center">Aucune notification.</li>';
                    } else {
                        notifs.forEach(n => {
                            list.innerHTML += `<li class="py-4 text-sm text-gray-700">${n.message || n.title}</li>`;
                        });
                    }
                })
                .catch(error => console.error('Erreur notifications:', error));
        });
    </script>
</x-app-layout>
