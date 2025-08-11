@extends('layouts.app')

@section('title', 'DCS Risk Register')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-table text-primary"></i>
                        DCS Enhanced Risk Register for Client Acceptance and Retention
                    </h1>
                    <p class="text-muted">Comprehensive risk assessment and monitoring system</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('client-risk.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                    <a href="{{ route('client-risk.clients.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Client
                    </a>
                    <button class="btn btn-success" onclick="exportToExcel()">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Comprehensive Risk Register Description -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Risk Register Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="lead mb-3">
                                <strong>DCS Enhanced Risk Register for Client Acceptance and Retention</strong>
                            </p>
                            <p class="text-justify">
                                This risk register identifies, assesses, and mitigates risks in client acceptance and retention while ensuring compliance with Namibia's Financial Intelligence Act (FIA) and Financial Intelligence Centre (FIC) requirements, including Anti-Money Laundering (AML), Counter-Terrorist Financing (CTF), and Know Your Customer (KYC) obligations.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-shield-alt"></i> Compliance Focus</h6>
                                <ul class="mb-0 small">
                                    <li>Financial Intelligence Act (FIA)</li>
                                    <li>Financial Intelligence Centre (FIC)</li>
                                    <li>Anti-Money Laundering (AML)</li>
                                    <li>Counter-Terrorist Financing (CTF)</li>
                                    <li>Know Your Customer (KYC)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comprehensive Risk Register Description -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Risk Register Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="lead mb-3">
                                <strong>DCS Enhanced Risk Register for Client Acceptance and Retention</strong>
                            </p>
                            <p class="text-justify">
                                This risk register identifies, assesses, and mitigates risks in client acceptance and retention while ensuring compliance with Namibia's Financial Intelligence Act (FIA) and Financial Intelligence Centre (FIC) requirements, including Anti-Money Laundering (AML), Counter-Terrorist Financing (CTF), and Know Your Customer (KYC) obligations.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-shield-alt"></i> Compliance Focus</h6>
                                <ul class="mb-0 small">
                                    <li>Financial Intelligence Act (FIA)</li>
                                    <li>Financial Intelligence Centre (FIC)</li>
                                    <li>Anti-Money Laundering (AML)</li>
                                    <li>Counter-Terrorist Financing (CTF)</li>
                                    <li>Know Your Customer (KYC)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comprehensive Risk Categories & Key Risks -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle"></i> Risk Categories & Key Risks
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>Risk ID</th>
                                    <th>Risk Description</th>
                                    <th>Risk Detail</th>
                                    <th>Risk Category</th>
                                    <th>Impact (H/M/L)</th>
                                    <th>Likelihood (H/M/L)</th>
                                    <th>Risk Rating (H/M/L)</th>
                                    <th>Mitigation Strategies</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Client Risks -->
                                <tr class="table-danger">
                                    <td><strong>CR-01</strong></td>
                                    <td>PIP / PEP client</td>
                                    <td>High-risk client (e.g., politically exposed person, high-net-worth individual)</td>
                                    <td><span class="badge badge-primary">Client Risk</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td>Enhanced Due Diligence (EDD), ongoing monitoring</td>
                                    <td>Compliance Officer</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>CR-02</strong></td>
                                    <td>Corporate client</td>
                                    <td>Corporate client with opaque ownership structure (beneficial ownership concerns)</td>
                                    <td><span class="badge badge-primary">Client Risk</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td>Verify UBOs (Ultimate Beneficial Owners), review corporate documents</td>
                                    <td>Compliance Officer</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>CR-03</strong></td>
                                    <td>Individual client</td>
                                    <td>Individual client with inconsistent documentation (ID, proof of address)</td>
                                    <td><span class="badge badge-primary">Client Risk</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td>Stricter KYC (Know Your Customer) requirements</td>
                                    <td>Compliance Officer</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                
                                <!-- Service Risks -->
                                <tr class="table-danger">
                                    <td><strong>SR-01</strong></td>
                                    <td>High-risk services</td>
                                    <td>High-risk services (e.g., large cash transactions, cross-border payments)</td>
                                    <td><span class="badge badge-success">Service Risk</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td>Specialized training, legal review, compliance checks</td>
                                    <td>Service Manager</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                <tr class="table-info">
                                    <td><strong>SR-02</strong></td>
                                    <td>Complex services</td>
                                    <td>Complex services with high regulatory scrutiny (e.g., tax advisory, financial planning)</td>
                                    <td><span class="badge badge-success">Service Risk</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td>Standardized checklists, periodic reviews</td>
                                    <td>Operations Manager</td>
                                    <td><span class="badge badge-success">Closed</span></td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>SR-03</strong></td>
                                    <td>Standard services</td>
                                    <td>Standard services with low complexity (lower risk but potential for complacency)</td>
                                    <td><span class="badge badge-success">Service Risk</span></td>
                                    <td><span class="badge badge-success">Low</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-success">Low</span></td>
                                    <td>Standardized checklists, periodic reviews</td>
                                    <td>Operations Manager</td>
                                    <td><span class="badge badge-success">Closed</span></td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>SR-04</strong></td>
                                    <td>Unrecorded face-to-face transactions</td>
                                    <td>Unrecorded face-to-face transactions (no audit trail)</td>
                                    <td><span class="badge badge-success">Service Risk</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td>Specialized training, legal review, compliance checks</td>
                                    <td>Compliance Officer</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                
                                <!-- Payment Risks -->
                                <tr class="table-danger">
                                    <td><strong>PR-01</strong></td>
                                    <td>Cash Payments</td>
                                    <td>Cash payments increasing money laundering risk</td>
                                    <td><span class="badge badge-info">Payment Risk</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td>Cash payment limits, mandatory reporting for large transactions</td>
                                    <td>Finance Team</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>PR-02</strong></td>
                                    <td>EFTs/SWIFT</td>
                                    <td>EFT/SWIFT payments (risk of fraud, incorrect beneficiary details)</td>
                                    <td><span class="badge badge-info">Payment Risk</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td>Dual approval for large transfers, client confirmation protocols</td>
                                    <td>Finance Team</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>PR-03</strong></td>
                                    <td>POS Payments</td>
                                    <td>POS payments (risk of chargebacks, disputes)</td>
                                    <td><span class="badge badge-info">Payment Risk</span></td>
                                    <td><span class="badge badge-success">Low</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-success">Low</span></td>
                                    <td>Clear refund policies, transaction records</td>
                                    <td>Finance Team</td>
                                    <td><span class="badge badge-success">Closed</span></td>
                                </tr>
                                
                                <!-- Delivery Risks -->
                                <tr class="table-danger">
                                    <td><strong>DR-01</strong></td>
                                    <td>Remote service risks</td>
                                    <td>Remote onboarding without proper identity verification</td>
                                    <td><span class="badge badge-secondary">Delivery Risk</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td>Multi-factor authentication (MFA), secure client portals</td>
                                    <td>IT Security</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>DR-02</strong></td>
                                    <td>Face-to-face service risks</td>
                                    <td>Face-to-face service risks (data security, physical safety)</td>
                                    <td><span class="badge badge-secondary">Delivery Risk</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-success">Low</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td>Secure document handling, staff training on confidentiality</td>
                                    <td>HR/Security</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Rating Methodology -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calculator"></i> Risk Rating Methodology
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-exclamation-circle"></i> Impact (I)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge badge-danger">High</span>
                                        <small class="text-muted">Legal penalties, reputational damage, major financial loss</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-warning">Medium</span>
                                        <small class="text-muted">Operational delays, moderate financial impact</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-success">Low</span>
                                        <small class="text-muted">Minor inconvenience, easily rectifiable</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-chart-line"></i> Likelihood (L)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge badge-danger">High</span>
                                        <small class="text-muted">Frequent occurrence (e.g., cash transactions in high-risk sectors)</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-warning">Medium</span>
                                        <small class="text-muted">Occasional occurrence</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-success">Low</span>
                                        <small class="text-muted">Rare occurrence</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Risk Rating (RR)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge badge-danger">High</span>
                                        <small class="text-muted">Immediate action required</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-warning">Medium</span>
                                        <small class="text-muted">Monitoring and controls needed</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-success">Low</span>
                                        <small class="text-muted">Acceptable with minimal controls</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Controls & Best Practices -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shield-alt"></i> Key Controls & Best Practices
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-info mb-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-users"></i> 1. Client Acceptance</h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="text-info">Corporate Clients</h6>
                                    <ul class="small">
                                        <li>Verify legal existence</li>
                                        <li>Proof of trading address</li>
                                        <li>List of owners (shareholders, members, beneficial owners)</li>
                                        <li>Risk Profiling</li>
                                        <li>Sanction screening</li>
                                    </ul>
                                    <h6 class="text-info">Individuals</h6>
                                    <ul class="small">
                                        <li>Validate ID</li>
                                        <li>Proof of residential address</li>
                                        <li>Risk Profiling</li>
                                        <li>Sanction screening</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success mb-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-truck"></i> 2. Service Delivery</h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="text-success">Online/Remote</h6>
                                    <ul class="small">
                                        <li>Secure portals</li>
                                        <li>Encryption</li>
                                        <li>Biometric verification</li>
                                    </ul>
                                    <h6 class="text-success">Face-to-Face</h6>
                                    <ul class="small">
                                        <li>Secure data handling</li>
                                        <li>Confidentiality agreements</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-warning mb-3">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-credit-card"></i> 3. Payment Methods</h6>
                                </div>
                                <div class="card-body">
                                    <p class="small"><strong>Cash:</strong> Limit acceptance, enforce AML reporting.</p>
                                    <p class="small"><strong>EFT/SWIFT:</strong> Dual authorization for large transfers.</p>
                                    <p class="small"><strong>POS:</strong> Clear dispute resolution process.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-eye"></i> 4. Ongoing Monitoring</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="small">
                                        <li>Periodic client reviews (especially high-risk clients)</li>
                                        <li>Staff training on fraud detection and compliance</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review & Approval Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-check"></i> Review & Approval
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Reviewed by:</strong></label>
                                <input type="text" class="form-control" placeholder="[Compliance Officer / Risk Manager]" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Approved by:</strong></label>
                                <input type="text" class="form-control" placeholder="[Senior Management]" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Next Review Date:</strong></label>
                                <input type="text" class="form-control" placeholder="[Quarterly/Annually]" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> This risk register ensures a structured approach to managing risks in client acceptance and retention while complying with regulatory requirements (e.g., AML, GDPR, industry-specific laws). Adjust based on your organization's specific risk appetite and policies.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comprehensive Risk Categories & Key Risks -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle"></i> Risk Categories & Key Risks
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>Risk ID</th>
                                    <th>Risk Description</th>
                                    <th>Risk Detail</th>
                                    <th>Risk Category</th>
                                    <th>Impact (H/M/L)</th>
                                    <th>Likelihood (H/M/L)</th>
                                    <th>Risk Rating (H/M/L)</th>
                                    <th>Mitigation Strategies</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Client Risks -->
                                <tr class="table-danger">
                                    <td><strong>CR-01</strong></td>
                                    <td>PIP / PEP client</td>
                                    <td>High-risk client (e.g., politically exposed person, high-net-worth individual)</td>
                                    <td><span class="badge badge-primary">Client Risk</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td>Enhanced Due Diligence (EDD), ongoing monitoring</td>
                                    <td>Compliance Officer</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>CR-02</strong></td>
                                    <td>Corporate client</td>
                                    <td>Corporate client with opaque ownership structure (beneficial ownership concerns)</td>
                                    <td><span class="badge badge-primary">Client Risk</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td>Verify UBOs (Ultimate Beneficial Owners), review corporate documents</td>
                                    <td>Compliance Officer</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>CR-03</strong></td>
                                    <td>Individual client</td>
                                    <td>Individual client with inconsistent documentation (ID, proof of address)</td>
                                    <td><span class="badge badge-primary">Client Risk</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td>Stricter KYC (Know Your Customer) requirements</td>
                                    <td>Compliance Officer</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                
                                <!-- Service Risks -->
                                <tr class="table-danger">
                                    <td><strong>SR-01</strong></td>
                                    <td>High-risk services</td>
                                    <td>High-risk services (e.g., large cash transactions, cross-border payments)</td>
                                    <td><span class="badge badge-success">Service Risk</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td>Specialized training, legal review, compliance checks</td>
                                    <td>Service Manager</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                <tr class="table-info">
                                    <td><strong>SR-02</strong></td>
                                    <td>Complex services</td>
                                    <td>Complex services with high regulatory scrutiny (e.g., tax advisory, financial planning)</td>
                                    <td><span class="badge badge-success">Service Risk</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td>Standardized checklists, periodic reviews</td>
                                    <td>Operations Manager</td>
                                    <td><span class="badge badge-success">Closed</span></td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>SR-03</strong></td>
                                    <td>Standard services</td>
                                    <td>Standard services with low complexity (lower risk but potential for complacency)</td>
                                    <td><span class="badge badge-success">Service Risk</span></td>
                                    <td><span class="badge badge-success">Low</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-success">Low</span></td>
                                    <td>Standardized checklists, periodic reviews</td>
                                    <td>Operations Manager</td>
                                    <td><span class="badge badge-success">Closed</span></td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>SR-04</strong></td>
                                    <td>Unrecorded face-to-face transactions</td>
                                    <td>Unrecorded face-to-face transactions (no audit trail)</td>
                                    <td><span class="badge badge-success">Service Risk</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td>Specialized training, legal review, compliance checks</td>
                                    <td>Compliance Officer</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                
                                <!-- Payment Risks -->
                                <tr class="table-danger">
                                    <td><strong>PR-01</strong></td>
                                    <td>Cash Payments</td>
                                    <td>Cash payments increasing money laundering risk</td>
                                    <td><span class="badge badge-info">Payment Risk</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td>Cash payment limits, mandatory reporting for large transactions</td>
                                    <td>Finance Team</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>PR-02</strong></td>
                                    <td>EFTs/SWIFT</td>
                                    <td>EFT/SWIFT payments (risk of fraud, incorrect beneficiary details)</td>
                                    <td><span class="badge badge-info">Payment Risk</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td>Dual approval for large transfers, client confirmation protocols</td>
                                    <td>Finance Team</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>PR-03</strong></td>
                                    <td>POS Payments</td>
                                    <td>POS payments (risk of chargebacks, disputes)</td>
                                    <td><span class="badge badge-info">Payment Risk</span></td>
                                    <td><span class="badge badge-success">Low</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-success">Low</span></td>
                                    <td>Clear refund policies, transaction records</td>
                                    <td>Finance Team</td>
                                    <td><span class="badge badge-success">Closed</span></td>
                                </tr>
                                
                                <!-- Delivery Risks -->
                                <tr class="table-danger">
                                    <td><strong>DR-01</strong></td>
                                    <td>Remote service risks</td>
                                    <td>Remote onboarding without proper identity verification</td>
                                    <td><span class="badge badge-secondary">Delivery Risk</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-danger">High</span></td>
                                    <td>Multi-factor authentication (MFA), secure client portals</td>
                                    <td>IT Security</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>DR-02</strong></td>
                                    <td>Face-to-face service risks</td>
                                    <td>Face-to-face service risks (data security, physical safety)</td>
                                    <td><span class="badge badge-secondary">Delivery Risk</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td><span class="badge badge-success">Low</span></td>
                                    <td><span class="badge badge-warning">Medium</span></td>
                                    <td>Secure document handling, staff training on confidentiality</td>
                                    <td>HR/Security</td>
                                    <td><span class="badge badge-warning">Open</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Rating Matrix -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Risk Rating Matrix</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>Risk ID</th>
                                    <th>Risk Description</th>
                                    <th>Risk Category</th>
                                    <th>Impact (H/M/L)</th>
                                    <th>Likelihood (H/M/L)</th>
                                    <th>Risk Rating (H/M/L)</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riskMatrix['client_risks'] as $riskId => $risk)
                                <tr class="table-light">
                                    <td><strong>{{ $riskId }}</strong></td>
                                    <td>{{ $risk['description'] }}</td>
                                    <td>Client Risk</td>
                                    <td>{{ strtoupper($risk['impact']) }}</td>
                                    <td>{{ strtoupper($risk['likelihood']) }}</td>
                                    <td>{{ strtoupper($risk['impact']) }}</td>
                                    <td><strong>{{ $risk['points'] }}</strong></td>
                                </tr>
                                @endforeach
                                @foreach($riskMatrix['service_risks'] as $riskId => $risk)
                                <tr class="table-light">
                                    <td><strong>{{ $riskId }}</strong></td>
                                    <td>{{ $risk['description'] }}</td>
                                    <td>Service Risk</td>
                                    <td>{{ strtoupper($risk['impact']) }}</td>
                                    <td>{{ strtoupper($risk['likelihood']) }}</td>
                                    <td>{{ strtoupper($risk['impact']) }}</td>
                                    <td><strong>{{ $risk['points'] }}</strong></td>
                                </tr>
                                @endforeach
                                @foreach($riskMatrix['payment_risks'] as $riskId => $risk)
                                <tr class="table-light">
                                    <td><strong>{{ $riskId }}</strong></td>
                                    <td>{{ $risk['description'] }}</td>
                                    <td>Payment Risk</td>
                                    <td>{{ strtoupper($risk['impact']) }}</td>
                                    <td>{{ strtoupper($risk['likelihood']) }}</td>
                                    <td>{{ strtoupper($risk['impact']) }}</td>
                                    <td><strong>{{ $risk['points'] }}</strong></td>
                                </tr>
                                @endforeach
                                @foreach($riskMatrix['delivery_risks'] as $riskId => $risk)
                                <tr class="table-light">
                                    <td><strong>{{ $riskId }}</strong></td>
                                    <td>{{ $risk['description'] }}</td>
                                    <td>Delivery Risk</td>
                                    <td>{{ strtoupper($risk['impact']) }}</td>
                                    <td>{{ strtoupper($risk['likelihood']) }}</td>
                                    <td>{{ strtoupper($risk['impact']) }}</td>
                                    <td><strong>{{ $risk['points'] }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Rating Methodology -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calculator"></i> Risk Rating Methodology
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-exclamation-circle"></i> Impact (I)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge badge-danger">High</span>
                                        <small class="text-muted">Legal penalties, reputational damage, major financial loss</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-warning">Medium</span>
                                        <small class="text-muted">Operational delays, moderate financial impact</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-success">Low</span>
                                        <small class="text-muted">Minor inconvenience, easily rectifiable</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-chart-line"></i> Likelihood (L)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge badge-danger">High</span>
                                        <small class="text-muted">Frequent occurrence (e.g., cash transactions in high-risk sectors)</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-warning">Medium</span>
                                        <small class="text-muted">Occasional occurrence</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-success">Low</span>
                                        <small class="text-muted">Rare occurrence</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Risk Rating (RR)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge badge-danger">High</span>
                                        <small class="text-muted">Immediate action required</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-warning">Medium</span>
                                        <small class="text-muted">Monitoring and controls needed</small>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge badge-success">Low</span>
                                        <small class="text-muted">Acceptable with minimal controls</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Controls & Best Practices -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shield-alt"></i> Key Controls & Best Practices
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-info mb-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-users"></i> 1. Client Acceptance</h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="text-info">Corporate Clients</h6>
                                    <ul class="small">
                                        <li>Verify legal existence</li>
                                        <li>Proof of trading address</li>
                                        <li>List of owners (shareholders, members, beneficial owners)</li>
                                        <li>Risk Profiling</li>
                                        <li>Sanction screening</li>
                                    </ul>
                                    <h6 class="text-info">Individuals</h6>
                                    <ul class="small">
                                        <li>Validate ID</li>
                                        <li>Proof of residential address</li>
                                        <li>Risk Profiling</li>
                                        <li>Sanction screening</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success mb-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-truck"></i> 2. Service Delivery</h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="text-success">Online/Remote</h6>
                                    <ul class="small">
                                        <li>Secure portals</li>
                                        <li>Encryption</li>
                                        <li>Biometric verification</li>
                                    </ul>
                                    <h6 class="text-success">Face-to-Face</h6>
                                    <ul class="small">
                                        <li>Secure data handling</li>
                                        <li>Confidentiality agreements</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-warning mb-3">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-credit-card"></i> 3. Payment Methods</h6>
                                </div>
                                <div class="card-body">
                                    <p class="small"><strong>Cash:</strong> Limit acceptance, enforce AML reporting.</p>
                                    <p class="small"><strong>EFT/SWIFT:</strong> Dual authorization for large transfers.</p>
                                    <p class="small"><strong>POS:</strong> Clear dispute resolution process.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-eye"></i> 4. Ongoing Monitoring</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="small">
                                        <li>Periodic client reviews (especially high-risk clients)</li>
                                        <li>Staff training on fraud detection and compliance</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review & Approval Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-check"></i> Review & Approval
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Reviewed by:</strong></label>
                                <input type="text" class="form-control" placeholder="[Compliance Officer / Risk Manager]" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Approved by:</strong></label>
                                <input type="text" class="form-control" placeholder="[Senior Management]" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Next Review Date:</strong></label>
                                <input type="text" class="form-control" placeholder="[Quarterly/Annually]" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> This risk register ensures a structured approach to managing risks in client acceptance and retention while complying with regulatory requirements (e.g., AML, GDPR, industry-specific laws). Adjust based on your organization's specific risk appetite and policies.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Risk Rating Criteria -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Overall Risk Rating Criteria</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>Points Total</th>
                                    <th>Overall Risk Rating</th>
                                    <th>Client Acceptance</th>
                                    <th>Ongoing Monitoring</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riskCriteria as $rating => $criteria)
                                <tr class="table-{{ $rating === 'very_high' ? 'danger' : ($rating === 'high' ? 'warning' : ($rating === 'medium' ? 'info' : 'success')) }}">
                                    <td><strong>{{ $criteria['min_points'] }}+</strong></td>
                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $rating)) }}</strong></td>
                                    <td>{{ $criteria['acceptance'] }}</td>
                                    <td>{{ $criteria['monitoring'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Risk Register Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Client Risk Register</h6>
                    <div class="d-flex gap-2">
                        <input type="text" id="clientFilter" class="form-control form-control-sm" placeholder="Filter clients..." style="width: 200px;">
                        <select id="riskFilter" class="form-control form-control-sm" style="width: 150px;">
                            <option value="">All Risk Levels</option>
                            <option value="low">Low Risk</option>
                            <option value="medium">Medium Risk</option>
                            <option value="high">High Risk</option>
                            <option value="very_high">Very High Risk</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="riskRegisterTable">
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2" class="text-center align-middle">No.</th>
                                    <th rowspan="2" class="text-center align-middle">Client Name</th>
                                    <th rowspan="2" class="text-center align-middle">Client ID Done?</th>
                                    <th colspan="3" class="text-center">Client Screening Done?</th>
                                    <th colspan="3" class="text-center">Requested Services?</th>
                                    <th colspan="5" class="text-center">Anticipated Payment Option?</th>
                                    <th colspan="5" class="text-center">Anticipated Service Delivery Method?</th>
                                    <th rowspan="2" class="text-center align-middle">Overall Risk Points</th>
                                    <th rowspan="2" class="text-center align-middle">Overall Risk Rating</th>
                                    <th rowspan="2" class="text-center align-middle">Client Acceptance</th>
                                    <th rowspan="2" class="text-center align-middle">Ongoing Monitoring</th>
                                    <th rowspan="2" class="text-center align-middle">DCS Risk Appetite</th>
                                    <th rowspan="2" class="text-center align-middle">DCS Comments</th>
                                    <th rowspan="2" class="text-center align-middle">Actions</th>
                                </tr>
                                <tr>
                                    <!-- Client Screening sub-headers -->
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Result</th>
                                    <th class="text-center">Description</th>
                                    
                                    <!-- Requested Services sub-headers -->
                                    <th class="text-center">Impact</th>
                                    <th class="text-center">Likelihood</th>
                                    <th class="text-center">Risk Rating</th>
                                    
                                    <!-- Payment Option sub-headers -->
                                    <th class="text-center">Risk ID</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Impact</th>
                                    <th class="text-center">Likelihood</th>
                                    <th class="text-center">Risk Rating</th>
                                    
                                    <!-- Service Delivery sub-headers -->
                                    <th class="text-center">Risk ID</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Impact</th>
                                    <th class="text-center">Likelihood</th>
                                    <th class="text-center">Risk Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clients as $index => $client)
                                <tr class="client-row" data-risk="{{ $client->overall_risk_rating }}">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $client->client_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $client->client_number }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($client->identification_done)
                                            <span class="badge badge-success">Yes</span>
                                            <br>
                                            <small>{{ $client->identification_date?->format('Y-m-d') }}</small>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $client->screening_date?->format('Y-m-d') ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        @if($client->screening_result)
                                            <span class="badge badge-{{ $client->screening_result === 'passed' ? 'success' : ($client->screening_result === 'failed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($client->screening_result) }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($client->screening_description, 50) ?? 'N/A' }}</small>
                                    </td>
                                    
                                    <!-- Service Risk Summary -->
                                    <td class="text-center">
                                        @php
                                            $serviceRisk = $client->services->max('risk_rating') ?? 'low';
                                        @endphp
                                        <span class="badge badge-{{ $serviceRisk === 'high' ? 'danger' : ($serviceRisk === 'medium' ? 'warning' : 'success') }}">
                                            {{ strtoupper($serviceRisk[0]) }}
                                        </span>
                                    </td>
                                    <td class="text-center">M</td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $serviceRisk === 'high' ? 'danger' : ($serviceRisk === 'medium' ? 'warning' : 'success') }}">
                                            {{ strtoupper($serviceRisk[0]) }}
                                        </span>
                                    </td>
                                    
                                    <!-- Payment Risk Summary -->
                                    <td class="text-center">
                                        @php
                                            $paymentRisk = $client->services->max('payment_method') === 'cash' ? 'high' : 'medium';
                                        @endphp
                                        PR-01
                                    </td>
                                    <td>
                                        <small>{{ $client->services->pluck('payment_method')->unique()->implode(', ') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $paymentRisk === 'high' ? 'danger' : 'warning' }}">
                                            {{ strtoupper($paymentRisk[0]) }}
                                        </span>
                                    </td>
                                    <td class="text-center">M</td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $paymentRisk === 'high' ? 'danger' : 'warning' }}">
                                            {{ strtoupper($paymentRisk[0]) }}
                                        </span>
                                    </td>
                                    
                                    <!-- Delivery Risk Summary -->
                                    <td class="text-center">
                                        @php
                                            $deliveryRisk = $client->services->contains('delivery_method', 'remote') ? 'high' : 'medium';
                                        @endphp
                                        DR-01
                                    </td>
                                    <td>
                                        <small>{{ $client->services->pluck('delivery_method')->unique()->implode(', ') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $deliveryRisk === 'high' ? 'danger' : 'warning' }}">
                                            {{ strtoupper($deliveryRisk[0]) }}
                                        </span>
                                    </td>
                                    <td class="text-center">M</td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $deliveryRisk === 'high' ? 'danger' : 'warning' }}">
                                            {{ strtoupper($deliveryRisk[0]) }}
                                        </span>
                                    </td>
                                    
                                    <!-- Overall Risk Summary -->
                                    <td class="text-center">
                                        <strong>{{ $client->total_risk_points }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $client->overall_risk_rating === 'very_high' ? 'danger' : ($client->overall_risk_rating === 'high' ? 'warning' : ($client->overall_risk_rating === 'medium' ? 'info' : 'success')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $client->overall_risk_rating)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $client->client_acceptance === 'accepted' ? 'success' : ($client->client_acceptance === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $client->client_acceptance)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <small>{{ $client->ongoing_monitoring_frequency ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $client->overall_risk_rating === 'very_high' ? 'danger' : ($client->overall_risk_rating === 'high' ? 'warning' : 'success') }}">
                                            {{ $client->overall_risk_rating === 'very_high' ? 'Reject' : ($client->overall_risk_rating === 'high' ? 'Review' : 'Accept') }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($client->risk_comments, 50) ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('client-risk.clients.show', $client->id) }}" class="btn btn-outline-primary btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('client-risk.clients.edit', $client->id) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-info btn-sm" onclick="performAssessment({{ $client->id }})" title="New Assessment">
                                                <i class="fas fa-calculator"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="20" class="text-center py-4">
                                        <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                                        <p class="text-muted">No clients found in the risk register.</p>
                                        <a href="{{ route('client-risk.clients.create') }}" class="btn btn-primary">Add First Client</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Interactive Features -->
<script>
function exportToExcel() {
    window.location.href = '{{ route("client-risk.export") }}';
}

function performAssessment(clientId) {
    if (confirm('Perform a new risk assessment for this client?')) {
        window.location.href = `/client-risk/clients/${clientId}/edit?assessment=true`;
    }
}

// Filter functionality
document.getElementById('clientFilter').addEventListener('input', function() {
    filterTable();
});

document.getElementById('riskFilter').addEventListener('change', function() {
    filterTable();
});

function filterTable() {
    const clientFilter = document.getElementById('clientFilter').value.toLowerCase();
    const riskFilter = document.getElementById('riskFilter').value;
    const rows = document.querySelectorAll('.client-row');
    
    rows.forEach(row => {
        const clientName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const riskLevel = row.getAttribute('data-risk');
        
        const matchesClient = clientName.includes(clientFilter);
        const matchesRisk = !riskFilter || riskLevel === riskFilter;
        
        row.style.display = matchesClient && matchesRisk ? '' : 'none';
    });
}

// Auto-refresh every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);
</script>

<style>
.table-sm td, .table-sm th {
    padding: 0.3rem;
    font-size: 0.875rem;
}

.table-dark th {
    background-color: #343a40;
    color: white;
    font-size: 0.75rem;
}

.badge {
    font-size: 0.75rem;
}

.client-row:hover {
    background-color: #f8f9fa;
}

.table-responsive {
    max-height: 600px;
    overflow-y: auto;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.table-danger {
    background-color: #f8d7da !important;
}

.table-warning {
    background-color: #fff3cd !important;
}

.table-success {
    background-color: #d1edff !important;
}

.table-info {
    background-color: #d1ecf1 !important;
}

.text-justify {
    text-align: justify;
}
</style>
@endsection 