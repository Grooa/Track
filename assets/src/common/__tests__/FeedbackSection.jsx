import { render, shallow } from 'enzyme';

import FeedbackSection from '../FeedbackSection';

describe('<FeedbackSection />', () => {
  let title;
  let text;
  let type;
  let link;

  beforeEach(() => {
    title = 'Hello world';
    text = 'Lorem lipsum dolor sit amed';
    type = 'error';
    link = { url: 'https://test.com/test', label: 'Test button' };
  });

  it('should render empty block properly', () => {
    const wrapper = render(<FeedbackSection/>);

    expect(wrapper).toMatchSnapshot();
  });

  it('should render with only prop `title` properly', () => {
    const wrapper = render(<FeedbackSection title={title}/>);

    expect(wrapper).toMatchSnapshot();
  });

  it('should render with only prop text properly', () => {
    const wrapper = render(<FeedbackSection text={text}/>);

    expect(wrapper).toMatchSnapshot();
  });

  it('should render with only prop link properly', () => {
    const wrapper = render(<FeedbackSection link={link}/>);

    expect(wrapper).toMatchSnapshot();
  });

  it('should render full block properly', () => {
    const wrapper = render(<FeedbackSection
      title={title}
      text={text}
      type={type}
      link={link}/>);

    expect(wrapper).toMatchSnapshot();
  });

  it('should render div when prop title is empty', () => {
    const wrapper = shallow(<FeedbackSection/>);

    expect(wrapper.find('section').length).toBe(0);
    expect(wrapper.find('div').length).toBe(1);
  });

  it('should render section when prop title is not empty', () => {
    const wrapper = shallow(<FeedbackSection title={title}/>);

    expect(wrapper.find('div').length).toBe(0);
    expect(wrapper.find('section').length).toBe(1);
  });

  it('should set prop `type` as className `feedback-{type}` on <div>', () => {
    const wrapper = shallow(<FeedbackSection type={type}/>);

    expect(wrapper.find('div').hasClass(`feedback-${type}`)).toBeTruthy();
  });

  it('should set prop `type` as className `feedback-{type}` on <section>', () => {
    const wrapper = shallow(<FeedbackSection title={title} type={type}/>);

    expect(wrapper.find('section').hasClass(`feedback-${type}`)).toBeTruthy();
  });
});
