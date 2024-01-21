import { COLORS, MONTHS, renderChart, chartBarOptions } from "./chart.js";

const ctxCharResales = document.getElementById("chart-resales");
const ctxCharManagers = document.getElementById("chart-managers");
const ctxCharClerk = document.getElementById("chart-clerk");
const ctxCharSalesByClerks = document.getElementById("chart-sales-by-clerks");

// chart resales
const configChartResales = {
  type: "bar",
  data: {
    labels: MONTHS,
    datasets: [
      {
        label: "AGO/23",
        data: [12, 19, 3, 5, 20, 15, 22, 20],
        borderWidth: 1,
        borderColor: COLORS.yellow,
        backgroundColor: COLORS.yellow,
        barThickness: 16,
      },
    ],
  },
  options: chartBarOptions,
};

const chartResales = renderChart(ctxCharResales, configChartResales);

// chart managers
const configChartManagers = {
  type: "bar",
  data: {
    labels: MONTHS,
    datasets: [
      {
        label: "AGO/23",
        data: [12, 19, 3, 5, 20, 15, 22, 20],
        borderWidth: 1,
        borderColor: COLORS.yellow,
        backgroundColor: COLORS.yellow,
        barThickness: 16,
      },
    ],
  },
  options: chartBarOptions,
};

const chartManagers = renderChart(ctxCharManagers, configChartManagers);

// chart clerk
const configChartClerk = {
  type: "bar",
  data: {
    labels: MONTHS,
    datasets: [
      {
        label: "AGO/23",
        data: [12, 19, 3, 5, 20, 15, 22, 20],
        borderWidth: 1,
        borderColor: COLORS.yellow,
        backgroundColor: COLORS.yellow,
        barThickness: 16,
      },
    ],
  },
  options: chartBarOptions,
};

const chartClerk = renderChart(ctxCharClerk, configChartClerk);

// chart sales by clerk
const configChartSalesByClerk = {
  type: "bar",
  data: {
    labels: MONTHS,
    datasets: [
      {
        label: "AGO/23",
        data: [12, 19, 3, 5, 20, 15, 22, 20],
        borderWidth: 1,
        borderColor: COLORS.yellow,
        backgroundColor: COLORS.yellow,
        barThickness: 16,
      },
    ],
  },
  options: chartBarOptions,
};

const chartSalesByClerk = renderChart(
  ctxCharSalesByClerks,
  configChartSalesByClerk
);
