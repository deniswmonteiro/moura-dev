import {
  renderChart,
  COLORS,
  MONTHS,
  chartLineOptions,
  chartBarOptions,
  chartRepresentationOptions,
  chartRepresentationPlugin,
} from "./chart.js";

const ctxCumulativeSales = document.getElementById("chart-cumulative-sales");
const ctxChartWalletActive = document.getElementById("chart-wallet-active");
const ctxChartRepresentation1 = document.getElementById("chart-representation-1");
const ctxChartRepresentation2 = document.getElementById("chart-representation-2");

// config chart cumulative sales
const configChartCumativeSales = {
  type: "line",
  data: {
    labels: MONTHS,
    datasets: [
      {
        label: "JAN/23",
        data: [5, 7, 14, 15, 20, 23, 28, 30],
        borderWidth: 4,
        borderColor: COLORS.yellow,
        backgroundColor: "rgba(255, 250, 242, 0.5)",
        pointBackgroundColor: COLORS.white,
        pointBorderWidth: 1,
        pointBorderColor: COLORS.yellow,
        fill: true,
      },
      {
        label: "JAN/22",
        data: [5, 9, 17, 16, 19, 25, 29, 25, 32],
        borderWidth: 1,
        borderColor: COLORS.gray,
        backgroundColor: COLORS.gray,
        pointBackgroundColor: COLORS.white,
        pointBorderWidth: 1,
        pointBorderColor: COLORS.gray,
      },
    ],
  },
  options: chartLineOptions,
};

// chart cumulative sales
const chartCumulativeSales = renderChart(
  ctxCumulativeSales,
  configChartCumativeSales
);

// config chart wallet active
const configChartWalletActive = {
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

// config chart wallet active
const chartWalletActive = renderChart(
  ctxChartWalletActive,
  configChartWalletActive
);

// chart represetation 1
const configChartRepresetation1 = {
  type: "doughnut",
  data: {
    datasets: [
      {
        percent: 63,
        backgroundColor: [COLORS.yellow, COLORS.white],
        borderWidth: 1,
      },
    ],
  },
  options: chartRepresentationOptions,
  plugins: [chartRepresentationPlugin],
};

const chartRepresentation1 = renderChart(
  ctxChartRepresentation1,
  configChartRepresetation1
);

// chart represetation 2
const configChartRepresetation2 = {
  type: "doughnut",
  data: {
    datasets: [
      {
        percent: 41,
        backgroundColor: [COLORS.yellow, COLORS.white],
        borderWidth: 1,
      },
    ],
  },
  options: chartRepresentationOptions,
  plugins: [chartRepresentationPlugin],
};

const chartRepresentation2 = renderChart(
  ctxChartRepresentation2,
  configChartRepresetation2
);
