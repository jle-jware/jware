export default {
    items: [
        {
            name: 'Home',
            url: '/',
            icon: 'icon-home',
            children: [
                {
                    name: 'My dashboard',
                    url: '/dashboard/',
                }
            ]
        },
        {
            name: 'Foundation',
            url: '/companies/',
            icon: 'fa fa-wrench',
            children: [
                {
                    name: 'Companies',
                    url: '/companies/',
                    icon: 'far fa-building',
                    meta: 'Technician',
                    meta: 'Manager'
                },
                {
                    name: 'Contacts',
                    url: '/users',
                    icon: 'fa fa-address-card',
                    meta: 'Technician',
                    meta: 'Manager'
                },
                {
                    name: 'Role Types',
                    url: '/roleTypes',
                    icon: 'fa fa-address-card',
                    meta: 'Technician',
                    meta: 'Manager'
                },
                {
                    name: 'Company Type List',
                    url: '/companyTypes/',
                    meta: 'Technician',
                    meta: 'Manager'
                },
            ]
        },
        {
            name: 'Assets',
            url: '/assets',
            icon: 'fa fa-cogs',
            children: [
                { name: 'Asset List', url: '/assets/' },
                { name: 'Document List', url: '/documents/',meta: 'Technician',meta: 'Manager' },
                { name: 'Spares List', url: '/spares/',meta: 'Technician',meta: 'Manager' },
                { name: 'Job List', url: '/jobs' },
                { name: 'Job Sheets', url: '/job-sheets',meta: 'Technician',meta: 'Manager' },
            ]
        },
        {
            name: 'Asset Settings',
            url: '/',
            icon: 'fas fa-sliders-h',
            children: [
                { 
                    name: 'Asset Model List', 
                    url: '/assetModels/',
                    meta: 'Technician',
                    meta: 'Manager'
                },
                { name: 'Model Type List', url: '/modelTypes/', meta: 'Technician',meta: 'Manager' },
                { name: 'Spare Type List', url: '/SpareTypes/', meta: 'Technician',meta: 'Manager'},
                { name: 'Document Type List', url: '/documentTypes/' ,meta: 'Technician',meta: 'Manager' },
                { name: 'Job Type List', url: '/jobTypes/',meta: 'Technician',meta: 'Manager' },
                { name: 'Job Status List', url: '/JobStats/', meta: 'Technician',meta: 'Manager' },
                { name: 'Job Priority List', url: '/priorities/', meta: 'Technician',meta: 'Manager' },
                { name: 'Milestone Type List', url: '/milestoneTypes/', meta: 'Technician',meta: 'Manager' },
                { name: 'Assest Status List', url: '/assetStats/' , meta: 'Technician',meta: 'Manager' },
                { name: 'Asset Options', url: '/machineOptions/' , meta: 'Technician',meta: 'Manager' },
                { name: 'Inspection Criteria', url: '/inspectionCriterias/', meta: 'Technician',meta: 'Manager' },
                { name: 'Region List', url: '/regions/', meta: 'Technician',meta: 'Manager' },
                { name: 'Result List', url: '/inspectionResultTypes/', meta: 'Technician',meta: 'Manager' },
                { name: 'Inspection Result List', url: '/inspectionResults/', meta: 'Technician',meta: 'Manager' },
                { name: 'Utilization Type List', url: '/utilizationTypes/', meta: 'Technician',meta: 'Manager' },
                // { name: 'Machine Spec Lists', url: '/machineSpecs/' },
            ]
        },
        {
            name: 'Help Center',
            url: '/help',
            icon: 'fas fa-hands-helping',
            children: [
                { name: 'Help Center', url: '/helpCenters/' },
            ]
        },
    ]
}