export const MONTHS = [
  "JAN",
  "FEV",
  "MAR",
  "ABR",
  "MAI",
  "JUN",
  "JUL",
  "AGO",
  "SET",
  "OUT",
  "NOV",
  "DEZ",
];

export const COLORS = {
  black: "#000000",
  yellow: "#FFBF31",
  gray: "#999999",
  white: "#FFFFFF",
  mercury: "#E5E5E5",
};

export const renderChart = (ctx, config) => {
  // default options
  Chart.defaults.font.family = "'Poppins', sans-serif";
  Chart.defaults.font.size = 8;
  Chart.defaults.font.lineHeight = 1.5;
  Chart.defaults.color = COLORS.black;

  return new Chart(ctx, config);
};

export const chartRepresentationOptions = {
  maintainAspectRatio: false,
  cutoutPercentage: 85,
  rotation: Math.PI / 2,
  cutout: "85%",
  aspectRatio: 1,
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      filter: (tooltipItem) => tooltipItem.index == 0,
    },
  },
};

export const chartRepresentationPlugin = {
  beforeInit: (chart) => {
    const dataset = chart.data.datasets[0];
    chart.data.labels = [dataset.label];
    dataset.data = [dataset.percent, 100 - dataset.percent];
  },
  beforeDraw: (chart) => {
    const { width, height, ctx } = chart;

    ctx.restore();

    const fontSize = (height / 85).toFixed(2);

    ctx.font = "bold " + fontSize + "rem 'Poppins', sans-serif";
    ctx.fillStyle = COLORS.black;
    ctx.textBaseline = "middle";

    const text = chart.data.datasets[0].percent + "%";
    const textX = Math.round((width - ctx.measureText(text).width) / 2);
    const textY = height / 2;

    ctx.fillText(text, textX, textY);
    ctx.save();
  },
};

// custom tooltip block
const getOrCreateTooltip = (chart) => {
  let tooltipElement = chart.canvas.parentNode.querySelector("div");
  let tooltipElementList;

  // create element if it doesn't exist
  if (!tooltipElement) {
    tooltipElement = document.createElement("div");
    tooltipElement.id = "chart-tooltip";
    tooltipElement.classList.add("chart-tooltip-custom");

    tooltipElementList = document.createElement("ul");
    tooltipElementList.classList.add("chart-tooltip-custom__list");
    tooltipElement.appendChild(tooltipElementList);

    chart.canvas.parentNode.appendChild(tooltipElement);
  }

  return tooltipElement;
};

// custom external tooltip
const externalTooltipHandler = (context) => {
  const { chart, tooltip } = context;

  // create trigger
  const tooltipElement = getOrCreateTooltip(chart);

  // hide if mouseout
  if (tooltip.opacity === 0) {
    tooltipElement.style.opacity = 0;
    return;
  }

  // create tooltip text
  if (tooltip.body) {
    const bodyLines = tooltip.body.map((b) => b.lines);

    // get list
    const tooltipElementList = document.getElementsByClassName(
      "chart-tooltip-custom__list"
    )[0];
    const tooltipListItem = document.createElement("li");

    // body
    const tooltipBodyParagraph = document.createElement("p");

    bodyLines.forEach((body, index) => {
      const displayBlockSpan = document.createElement("span");
      displayBlockSpan.classList.add("chart-tooltip-custom__item-block");

      const colors = tooltip.labelColors[index];
      const spanCircle = document.createElement("span");
      spanCircle.classList.add("chart-tooltip-custom__color-square");
      spanCircle.style.backgroundColor = colors.backgroundColor;
      spanCircle.style.borderColor = colors.borderColor;

      const tooltipBodyLabel = document.createElement("span");
      tooltipBodyLabel.classList.add("chart-tooltip-custom__item-label");

      const tooltipBodyValue = document.createElement("span");
      tooltipBodyValue.classList.add("chart-tooltip-custom__item-value");
      tooltipBodyValue.style.color = colors.borderColor;

      // create a text node for the body
      const textLabel = document.createTextNode(body);
      const textLabelFormatted = textLabel.textContent.split(": ");

      const textLabel1 = document.createTextNode(
        textLabelFormatted[0].toString()
      );
      const textLabel2 = document.createTextNode(
        textLabelFormatted[1].toString()
      );

      tooltipBodyLabel.appendChild(textLabel1);
      tooltipBodyValue.appendChild(textLabel2);

      displayBlockSpan.appendChild(spanCircle);
      displayBlockSpan.appendChild(tooltipBodyLabel);
      tooltipBodyParagraph.appendChild(displayBlockSpan);
      tooltipBodyParagraph.appendChild(tooltipBodyValue);
    });

    // remove first child from the list
    while (tooltipElementList.firstChild) {
      tooltipElementList.firstChild.remove();
    }

    // add new children to the list
    tooltipElementList.appendChild(tooltipListItem);
    tooltipListItem.appendChild(tooltipBodyParagraph);
    tooltipElement.style.opacity = 1;

    // positioning of the tooltip
    const { offsetLeft: positionX, offsetTop: positionY } = chart.canvas;
    tooltipElement.style.position = "absolute";
    tooltipElement.style.zIndex = 1;
    tooltipElement.style.top = positionY - 55 + tooltip.caretY + "px";
    tooltipElement.style.left = positionX + 100 + tooltip.caretX + "px";
    tooltipElement.style.font = tooltip.options.bodyFont.string;
    tooltipElement.style.padding =
      tooltip.options.padding + "px" + tooltip.options.padding + "px";
  }
};

// chart line options
export const chartLineOptions = {
  scales: {
    x: {
      offset: true,
      grid: {
        display: false,
        drawOnChartArea: false,
        drawTicks: false,
        z: 1,
      },
      border: {
        width: 1,
        color: COLORS.black,
      },
      ticks: {
        padding: 10
      },
    },
    y: {
      grid: {
        display: true,
        lineWidth: 1,
        borderColor: COLORS.mercury,
        drawTicks: false,
      },
      border: {
        width: 0,
        dash: [1, 1],
        dashOffset: 1,
      },
      ticks: {
        display: false,
        major: {
          enabled: false,
        },
      },
    },
  },
  interaction: {
    mode: "index",
    intersect: false,
  },
  plugins: {
    legend: {
      display: false,
    },
  },
};

// char bar options
export const chartBarOptions = {
  scales: {
    x: {
      grid: {
        display: false,
        drawOnChartArea: false,
        drawTicks: false,
      },
      border: {
        width: 1,
        color: COLORS.black,
      },
      ticks: {
        padding: 10
      },
    },
    y: {
      grid: {
        display: true,
        lineWidth: 1,
        borderColor: COLORS.mercury,
        drawTicks: false,
      },
      border: {
        width: 0,
        dash: [1, 1],
        dashOffset: 1,
      },
      ticks: {
        display: false,
        major: {
          enabled: false,
        },
      },
    },
  },
  plugins: {
    legend: {
      display: false,
    },
  },
};
