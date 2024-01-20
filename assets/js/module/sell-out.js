import {
  COLORS,
  MONTHS,
  renderChart,
  chartLineOptions,
  chartRepresentationOptions,
  chartRepresentationPlugin,
} from "./chart.js";

const ctxChartSellOut = document.getElementById("chart-sell-out");
const ctxChartSellInVersusSellOut = document.getElementById(
  "chart-sell-in-x-out"
);
const ctxChartRepresentation1 = document.getElementById(
  "chart-representation-1"
);

// config chart sell out
const configCharSellout = {
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

// chart
const chartSellOut = renderChart(ctxChartSellOut, configCharSellout);

// chart sell in versus sell out
const configChartSellIntVersusSellOut = {
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

// chart
const chartSellOutVersusSellIn = renderChart(
  ctxChartSellInVersusSellOut,
  configChartSellIntVersusSellOut
);

// chart representation 1
const configChartRepresetation1 = {
  type: "doughnut",
  data: {
    datasets: [
      {
        percent: 15,
        backgroundColor: [COLORS.yellow, COLORS.white],
        borderWidth: 1,
      },
    ],
  },
  options: chartRepresentationOptions,
  plugins: [chartRepresentationPlugin],
};

// chart
const chartRepresentation1 = renderChart(
  ctxChartRepresentation1,
  configChartRepresetation1
);
