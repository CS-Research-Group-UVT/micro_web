# Microdeploy SPAs Comparison

These branches contains the single-page applications (SPAs) used in the research study **"Bridging Content Management and Micro-Frontends: A WordPress-Centric Approach to Modern Web Architecture"**, accepted at **AINA 2025**. The study evaluates the feasibility, performance, and security implications of deploying SPAs as micro-frontends within a WordPress ecosystem using a custom-built plugin.

## 📋 Overview

The SPAs in this repo serve as test cases to evaluate the performance and interoperability of different modern frontend frameworks when integrated into WordPress via a unified plugin-based deployment model.

### Frameworks Covered

- **React JS**
- **Vue JS**
- **Angular**
- **Vanilla JS**

Each SPA has two variants (where applicable):
- With lazy loading
- Without lazy loading

These builds were benchmarked against **Elementor-based** WordPress pages to compare performance.

## 📊 Benchmark Metrics

Performance was analyzed using [Google Lighthouse](https://developer.chrome.com/docs/lighthouse/overview) with the following metrics:

- **LCP** – Largest Contentful Paint  
- **FCP** – First Contentful Paint  
- **TBT** – Total Blocking Time  
- **SI** – Speed Index  
- **DCL** – DOM Content Loaded  

Test environment:
- OS: Ubuntu 24.04.1 LTS  
- RAM: 16GB  
- Browser: Microsoft Edge (incognito, no cache)  

The results showed up to **50% faster LCP** and **40% faster DOM construction times** for SPAs compared to Elementor-based implementations.

## 📁 Structure

Each folder contains a production-ready build of an SPA created using a different framework. These builds are meant to be imported into the custom WordPress plugin for deployment and testing.

Example folder layout:
```
/react-lazy/
  ├── index.html
  ├── static/
  └── assets/
```

## 📦 Usage

To deploy one of these SPAs:

1. Zip the folder (`build/`, `dist/`, or equivalent).
2. Upload it using the plugin interface developed in the companion repo: [micro_web](https://github.com/CS-Research-Group-UVT/micro_web).
3. Configure the URL slug in WordPress and monitor logs or performance metrics.

## 🔐 Security Considerations

The plugin used for deployment includes:
- Automatic URL path correction
- XSS protection via JSON sanitation
- Static asset delivery protection against path traversal attacks

## 📖 Citation

If you reference this work, please cite the following:

```bibtex
@inproceedings{secrieru2025microfrontends,
  author    = {Ștefan Secrieru and Sebastian-Aurelian Ștefănigă},
  title     = {Bridging Content Management and Micro-Frontends: A WordPress-Centric Approach to Modern Web Architecture},
  booktitle = {Proceedings of the 37th IEEE International Conference on Advanced Information Networking and Applications (AINA)},
  year      = {2025},
  note      = {Forthcoming},
}
```

## 📬 Contact

**Ștefan Secrieru**  
Email: stefan.secrieru@e-uvt.ro
GitHub: [@Stefan3002](https://github.com/Stefan3002)
