@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('subtitle', 'Overview Sistem Monitoring Magang')

@section('content')
<div class="stats-grid">
    <!-- Card 1 - Peserta Aktif -->
    <div class="stat-card border-blue">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="totalPeserta">124</div>
                <div class="stat-label">Peserta Aktif</div>
            </div>
            <div class="stat-icon blue">
                <i class='bx bx-user'></i>
            </div>
        </div>
    </div>
    
    <!-- Card 2 - Bidang Tersedia -->
    <div class="stat-card border-green">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="totalBidang">18</div>
                <div class="stat-label">Bidang Tersedia</div>
            </div>
            <div class="stat-icon green">
                <i class='bx bx-briefcase'></i>
            </div>
        </div>
    </div>
    
    <!-- Card 3 - Mentor -->
    <div class="stat-card border-orange">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="totalMentor">42</div>
                <div class="stat-label">Mentor Berpengalaman</div>
            </div>
            <div class="stat-icon orange">
                <i class='bx bx-user-voice'></i>
            </div>
        </div>
    </div>
    
    <!-- Card 4 - Alumni -->
    <div class="stat-card border-purple">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="totalAlumni">890</div>
                <div class="stat-label">Alumni Sukses</div>
            </div>
            <div class="stat-icon purple">
                <i class='bx bx-trophy'></i>
            </div>
        </div>
    </div>
</div>

<!-- Distribusi Peserta per Bidang -->
<div class="form-card mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-primary flex items-center gap-2">
            <i class='bx bx-pie-chart'></i> Distribusi Peserta per Bidang
        </h3>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pie Chart -->
        <div class="flex justify-center">
            <div id="pieChartContainer" class="w-full max-w-md"></div>
        </div>
        
        <!-- Detail Bidang -->
        <div>
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <div class="font-medium text-gray-800">Bidang Informatika</div>
                        </div>
                        <div class="text-lg font-bold text-primary" id="bidang-informatika">12</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500" id="bidang-informatika-bar" style="width: 48%"></div>
                        </div>
                        <span class="text-sm text-gray-600" id="bidang-informatika-persen">48%</span>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-teal-500 rounded-full"></div>
                            <div class="font-medium text-gray-800">Bidang Statistik</div>
                        </div>
                        <div class="text-lg font-bold text-primary" id="bidang-statistik">8</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-teal-500" id="bidang-statistik-bar" style="width: 32%"></div>
                        </div>
                        <span class="text-sm text-gray-600" id="bidang-statistik-persen">32%</span>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                            <div class="font-medium text-gray-800">Kesekretariatan</div>
                        </div>
                        <div class="text-lg font-bold text-primary" id="bidang-sekretariat">3</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-500" id="bidang-sekretariat-bar" style="width: 12%"></div>
                        </div>
                        <span class="text-sm text-gray-600" id="bidang-sekretariat-persen">12%</span>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                            <div class="font-medium text-gray-800">E-Goverment</div>
                        </div>
                        <div class="text-lg font-bold text-primary" id="bidang-egov">2</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-500" id="bidang-egov-bar" style="width: 8%"></div>
                        </div>
                        <span class="text-sm text-gray-600" id="bidang-egov-persen">8%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Data for pie chart - from bidang distribution
const bidangData = [
    { name: 'Informatika', value: 12, color: '#3b82f6' },
    { name: 'Statistik', value: 8, color: '#14b8a6' },
    { name: 'Kesekretariatan', value: 3, color: '#8b5cf6' },
    { name: 'E-Goverment', value: 2, color: '#f59e0b' }
];

// Initialize pie chart
function initPieChart() {
    const container = document.getElementById('pieChartContainer');
    if (!container) return;
    
    const total = bidangData.reduce((sum, item) => sum + item.value, 0);
    const size = 300;
    const radius = size / 2;
    
    const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("width", "100%");
    svg.setAttribute("height", "300");
    svg.setAttribute("viewBox", `0 0 ${size} ${size}`);
    
    let cumulativeAngle = 0;
    const centerX = size / 2;
    const centerY = size / 2;
    
    bidangData.forEach((item, index) => {
        const percentage = item.value / total;
        const angle = percentage * 360;
        const startAngle = cumulativeAngle;
        const endAngle = startAngle + angle;
        
        // Calculate points for arc
        const startAngleRad = (startAngle - 90) * Math.PI / 180;
        const endAngleRad = (endAngle - 90) * Math.PI / 180;
        
        const x1 = centerX + radius * Math.cos(startAngleRad);
        const y1 = centerY + radius * Math.sin(startAngleRad);
        const x2 = centerX + radius * Math.cos(endAngleRad);
        const y2 = centerY + radius * Math.sin(endAngleRad);
        
        // Create path
        const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
        const largeArcFlag = angle > 180 ? 1 : 0;
        
        const d = [
            `M ${centerX} ${centerY}`,
            `L ${x1} ${y1}`,
            `A ${radius} ${radius} 0 ${largeArcFlag} 1 ${x2} ${y2}`,
            "Z"
        ].join(" ");
        
        path.setAttribute("d", d);
        path.setAttribute("fill", item.color);
        path.setAttribute("stroke", "white");
        path.setAttribute("stroke-width", "2");
        path.setAttribute("class", "cursor-pointer hover:opacity-90 transition-opacity");
        path.setAttribute("data-index", index);
        
        svg.appendChild(path);
        
        cumulativeAngle = endAngle;
    });
    
    // Add center circle
    const centerCircle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    centerCircle.setAttribute("cx", centerX);
    centerCircle.setAttribute("cy", centerY);
    centerCircle.setAttribute("r", radius * 0.3);
    centerCircle.setAttribute("fill", "white");
    svg.appendChild(centerCircle);
    
    // Add total text
    const totalText = document.createElementNS("http://www.w3.org/2000/svg", "text");
    totalText.setAttribute("x", centerX);
    totalText.setAttribute("y", centerY - 5);
    totalText.setAttribute("text-anchor", "middle");
    totalText.setAttribute("dominant-baseline", "middle");
    totalText.setAttribute("fill", "#1a3a5f");
    totalText.setAttribute("font-size", "24");
    totalText.setAttribute("font-weight", "bold");
    totalText.textContent = total;
    svg.appendChild(totalText);
    
    const totalLabel = document.createElementNS("http://www.w3.org/2000/svg", "text");
    totalLabel.setAttribute("x", centerX);
    totalLabel.setAttribute("y", centerY + 15);
    totalLabel.setAttribute("text-anchor", "middle");
    totalLabel.setAttribute("dominant-baseline", "middle");
    totalLabel.setAttribute("fill", "#666");
    totalLabel.setAttribute("font-size", "12");
    totalLabel.textContent = "Total Peserta";
    svg.appendChild(totalLabel);
    
    container.innerHTML = '';
    container.appendChild(svg);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initPieChart();
});
</script>
@endsection