<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="flex flex-col gap-8 m-8" x-data="chartApp()">
    <h1 class="text-4xl font-bold">Graphy</h1>
    <p>Bon retour sur Graphy !</p>

    <form @submit.prevent="generateChart" class="flex flex-col gap-4">
        <div>
            <template x-for="(dataset, datasetIndex) in datasets" :key="datasetIndex">
                <div class="mb-4 p-4 border rounded bg-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-semibold">Dataset <span x-text="datasetIndex + 1"></span></h3>
                        <button type="button" class="p-2 bg-red-500 text-white rounded" @click="removeDataset(datasetIndex)">Supprimer</button>
                    </div>
                    <div>
                        <label for="chartType" class="block">Type de graphique:</label>
                        <select x-model="dataset.type" class="p-2 border rounded">
                            <option value="bar">Bar</option>
                            <option value="line">Line</option>
                            <option value="pie">Pie</option>
                            <option value="doughnut">Doughnut</option>
                            <option value="polarArea">Polar Area</option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <label class="block">Étiquettes et Données:</label>
                        <template x-for="(label, index) in dataset.labels" :key="index">
                            <div class="flex items-center mb-2">
                                <input type="text" class="p-2 border rounded w-1/2 mr-2" x-model="dataset.labels[index]" placeholder="Étiquette">
                                <input type="number" class="p-2 border rounded w-1/2" x-model="dataset.data[index]" placeholder="Valeur">
                            </div>
                        </template>
                        <button type="button" class="p-2 bg-green-300 text-white rounded w-full" @click="addLabelAndData(datasetIndex)">Ajouter une étiquette et une donnée</button>
                    </div>
                </div>
            </template>
            <button type="button" class="p-2 bg-green-500 text-white rounded w-full" @click="addDataset">Ajouter un set de données</button>
        </div>
        <button type="submit" class="p-2 bg-blue-500 text-white rounded w-full mt-4">Créer le graphique</button>
    </form>

    <div class="grid grid-cols-1 gap-8 mt-8">
        <div>
            <canvas id="userChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function chartApp() {
            return {
                datasets: [{
                    type: 'bar',
                    labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin'],
                    data: [12, 19, 3, 5, 2, 3]
                }],
                chartInstance: null,
                addDataset() {
                    this.datasets.push({
                        type: 'bar',
                        labels: [],
                        data: []
                    });
                },
                removeDataset(index) {
                    this.datasets.splice(index, 1);
                },
                addLabelAndData(datasetIndex) {
                    this.datasets[datasetIndex].labels.push('');
                    this.datasets[datasetIndex].data.push(0);
                },
                generateChart() {
                    const chartData = {
                        labels: this.datasets[0].labels,
                        datasets: this.datasets.map((dataset, datasetIndex) => {
                            const colors = [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ];
                            const borderColor = [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ];
                            const backgroundColors = dataset.data.map((_, index) => colors[index % colors.length]);
                            const borderColors = dataset.data.map((_, index) => borderColor[index % borderColor.length]);

                            return {
                                label: `Données ${datasetIndex + 1}`,
                                data: dataset.data,
                                backgroundColor: backgroundColors,
                                borderColor: borderColors,
                                borderWidth: 1,
                                type: dataset.type
                            };
                        })
                    };

                    const ctx = document.getElementById('userChart').getContext('2d');

                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                    }

                    this.chartInstance = new Chart(ctx, {
                        type: this.datasets[0].type,
                        data: chartData,
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            };
        }
    </script>
</body>

</html>